<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class EnvioFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    
    public function definition()
    {
        return [
            'numero_reg' => $this->faker->numberBetween(1000, 9000),
            'id_remitente' => $this->faker->numberBetween(6, 7),
            'id_receptor' => $this->faker->numberBetween(5, 6),
            'fecha_salida' => $this->faker->dateTimeThisMonth(),
            'fecha_recojo' => $this->faker->dateTimeThisMonth(),
            'desde_distrito' => $this->faker->numberBetween(1, 100),
            'desde_direccion' => $this->faker->streetAddress(),
            'hasta_distrito' => $this->faker->numberBetween(1, 100),
            'hasta_direccion' => $this->faker->streetAddress(),
            'cantidad' => $this->faker->randomDigit(),
            'descripcion' => $this->faker->sentence(),
            'precio' => $this->faker->randomFloat(2, 10, 100),
            'pago_estado' => $this->faker->numberBetween(1, 2),
            'envio_estado' => $this->faker->numberBetween(1, 2),
        ];
    }
}
