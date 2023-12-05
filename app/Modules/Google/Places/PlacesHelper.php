<?php


namespace App\Modules\Google\Places;


class PlacesHelper
{
    const NOMBRES_PROVINCIAS = [
        'Buenos Aires',
        'Capital Federal',
        'Catamarca',
        'Chaco',
        'Chubut',
        'Córdoba',
        'Corrientes',
        'Entre Ríos',
        'Formosa',
        'Jujuy',
        'La Pampa',
        'La Rioja',
        'Mendoza',
        'Misiones',
        'Neuquén',
        'Río Negro',
        'Salta',
        'San Juan',
        'San Luis',
        'Santa Cruz',
        'Santa Fe',
        'Santiago del Estero',
        'Tierra del Fuego',
        'Tucumán',
    ];

    /**
     * @param string $nombre
     * @return false|string
     */
    static public function mapperNombresProvincia(string $nombre): string
    {
        foreach(self::NOMBRES_PROVINCIAS as $provincia) {
            if (preg_match("/{$provincia}/i", $nombre)) {
                return $provincia;
            }
        }
        return $nombre; 
    }
}
