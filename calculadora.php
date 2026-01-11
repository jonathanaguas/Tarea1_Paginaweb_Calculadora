<?php
// calculadora.php

// Captura POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $expresion = isset($_POST['expresion']) ? $_POST['expresion'] : '';
    $resultado = 'Expresión inválida';

    // Normalización básica
    $expresion = str_replace(['×', '÷', ','], ['*', '/', '.'], $expresion);
    $expresion = trim($expresion);

    // Validación: solo dígitos, operadores + - * /, punto, espacios y paréntesis
    // Usamos delimitador ~ para evitar conflictos con /
    if (preg_match('~^[\d\+\-\*\/\.\(\)\s]+$~', $expresion)) {

        // Evitar operadores consecutivos inválidos (ej: ++, **, //, ..)
        if (!preg_match('~[\+\-\*\/]{2,}~', $expresion) && !preg_match('~\.{2,}~', $expresion)) {

            // Manejo de errores de eval sin mostrar warnings
            $evalOk = false;
            $valor = null;

            // Capturar errores de eval
            set_error_handler(function () { /* suprime warnings de eval */ });

            // Evalúa retornando el valor
            $valor = @eval('return ' . $expresion . ';');

            restore_error_handler();

            // Si eval devuelve algo numérico, es válido
            if (is_numeric($valor)) {
                $resultado = $valor;
            } else {
                $resultado = 'Error en la expresión';
            }
        } else {
            $resultado = 'Operadores inválidos consecutivos';
        }
    } else {
        $resultado = 'Expresión inválida';
    }

    // Salida con estilo con
    echo "<!DOCTYPE html><html lang='es'><head><meta charset='UTF-8'><title>Resultado</title>
          <link rel='stylesheet' href='estilos.css'></head><body>
          <div class='resultado-container'>
            <h1 class='resultado-titulo'>Resultado: {$resultado}</h1>
            <a class='volver-link' href='index.html'>Volver</a>
          </div>
          </body></html>";
    exit;
}
?>