<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Base\Repository;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Kirschbaum\PowerJoins\PowerJoins;



/**
 * Description of ModelRepository
 *
 * @author salomon
 */
class ModelRepository extends Model {

    use PowerJoins;

    static public function listar(int $page = 1, int $limit = 0, array $filtros = [], array $ordenes = [], array $opciones = []) {
        $builder = static::aplicarListado($filtros, $ordenes, $opciones);
        if ($limit > 0) {
            return static::aplicarPaginado($builder, $page, $limit, $opciones);
        }
        return $builder->get();
    }

    static public function listarTodos(array $filtros = [], array $ordenes = []): Collection {
        return static::listar(1, 0, $filtros, $ordenes);
    }

    static public function generarConsulta(array $filtros = [], array $ordenes = [], array $opciones = []): Builder {
        if (!empty($opciones['with_relation'])) {
            $query = static::with($opciones['with_relation']);
        } else {
            $query = (new static)->newModelQuery();
        }
        if (!empty($opciones['load'])) {
            $query->load($opciones['load']);
        }
        return $query;
    }

    public function table() {
        return $this->table;
    }

    static public function aplicarListado(array $filtros = [], array $ordenes = [], array $opciones = []): Builder {
        $query = static::generarConsulta($filtros, $ordenes, $opciones);

        static::aplicarFiltros($query, $filtros);
        static::aplicarOrdenes($query, $ordenes);
        
        if (DB::transactionLevel()) {
            $query->lockForUpdate();
        }

        return $query;
    }

    static public function aplicarPaginado(Builder $query, int $page = 1, int $limit = 0, array $opciones = []): LengthAwarePaginator {
        $paginated = $query->paginate($limit, ['*'], 'page', $page);

        foreach ($paginated->items() as $item) {
        	static::aplicarOpciones($item, $opciones);
		}

        return $paginated;
    }

    static public function contar(array $filtros = [], array $ordenes = []) {
        $query = static::aplicarListado($filtros, $ordenes);
        return $query->count();
    }

    static public function filtrosEq(): array {
        return ['id'];
    }

    static public function aplicarFiltros(Builder $query, array $filtros) {
        $table = (new static())->getTable();
        $filtrosEq = static::filtrosEq();
        foreach($filtros as $nombre => $valor) {
            if (in_array($nombre, $filtrosEq)) {
                $query->where("{$table}.{$nombre}", $valor);
            }

            if ($nombre === 'id_not' && $valor) {
                $query->where("{$table}.id", '<>', $valor);
            }
        }

        if ((new static)->hasSoftDelete()) {
            if (empty($filtros['borrados'])) {
                $query->where("{$table}.deleted_at", null);
            }
        }
    }

    static public function aplicarOpciones($model, array $opciones = []) {
        if (!empty($opciones['expandir'])) {
            $atributos = is_array($opciones['expandir']) ? $opciones['expandir'] : [$opciones['expandir']];
            foreach($atributos as $atributo) {
                $model->append($atributo);
            }
        }
    }

    static public function aplicarOrdenes(Builder $query, array $ordenes) {

    }

    /**
     *
     * @param int $id
     * @param array $filtros
     * @return static
     * @throws RepositoryException
     */
    static public function getById(int $id, array $filtros = [], array $opciones = []): self {
        return static::getOne(array_merge($filtros, [
            'id' => $id,
        ]), [], $opciones);
    }

    /**
     *
     * @param array $filtros
     * @param array $ordenes
     * @return \static
     * @throws RepositoryException
     */
    static public function getOne(array $filtros = [], array $ordenes = [], array $opciones = []): self {
        $rs = static::listar(1, 1, $filtros, $ordenes, $opciones);
        if ($rs->total() === 0) {
            throw new NotFound('No se encontró el registro');
        }

        if ($rs->total() > 1) {
            throw new NotFound('No se encontró el registro');
        }

        return $rs->first();
    }

    /**
     *
     * @param array $filtros
     * @param array $ordenes
     * @return \static
     * @throws RepositoryException
     */
    static public function getFirst(array $filtros = [], array $ordenes = [], array $opciones = []): self {
        $rs = static::listar(1, 1, $filtros, $ordenes, $opciones);
        if ($rs->total() === 0) {
            throw new RepositoryException('No se encontró el registro');
        }

        return $rs->first();
    }

    public function insertar(): self {
        try {
            if (!$this->save()) {
                throw new RepositoryException("No se pudo crear el registro.", 0);
            }
        } catch (\Throwable $e) {
            throw new RepositoryException("No se pudo crear el registro: {$e->getMessage()}", 0, $e);
        }

        return $this;
    }

    public function guardar(): self {
        try {
            if (!$this->save()) {
                throw new RepositoryException("No se pudo actualizar el registro.", 0);
            }
        } catch (\Throwable $e) {
            throw new RepositoryException("No se pudo actualizar el registro: {$e->getMessage()}", 0, $e);
        }

        return $this;
    }

    /**
     * @throws \Exception
     */
    public function borrar(): bool
    {
        $deleted = false;

        if ($this->hasSoftDelete()) {
            $deleted = $this->delete();
        } else {
            $deleted = $this->newModelQuery()
                ->where($this->getKeyName(), $this->id)
                ->delete();
        }

        if (!$deleted) {
            throw new RepositoryException("No se pudo borrar el registro.", 0);
        }

        return true;
    }

    /**
     * @return bool
     */
    public function hasSoftDelete() {
        return method_exists($this, 'getDeletedAtColumn');
    }

	static protected function alredyJoined(Builder $query, string $table) {
		$joins = $query->getQuery()->joins;

		if ($joins == null) {
			return false;
		}

		foreach ($joins as $join) {
			if ($join->table == $table) {
				return true;
			}
		}

		return false;
	}

	static protected function join(Builder $query, string $table, string $first, string $operator, string $second) {
    	if (!static::alredyJoined($query, $table)) {
    		$query->join($table, $first, $operator, $second);
		}

    	return $query;
	}
}
