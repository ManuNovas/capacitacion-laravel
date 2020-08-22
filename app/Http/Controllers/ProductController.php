<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Validamos si el parámetro $id de la ruta es mayor a 0
     *  Si es mayor a 0, retornamos la vista con las variables $id y $hola
     *  Si es menor o igual a 0, retornamos mensaje de error
     * @param integer $id
     * @return Application|Factory|View|string
     */
    public function get($id)
    {
        if ($id > 0) {
            $hola = 'Hola';
            // compact regresa un arreglo con las variables -> ['id' => $id, 'hola' => $hola]
            return view('formulario')->with(compact('id', 'hola'));
        } else {
            return 'ID Inválido';
        }
    }

    /**
     * Lógica de negocio para publicar el producto desde el form de la vista formulario
     * @param Request $request
     * @return JsonResponse
     */
    public function post(Request $request)
    {
        // Validar datos de entrada
        //$request->validate(['precio' => 'numeric', 'archivo' => 'file|mimes:jpeg,png,gif']);

        // Obtener el nombre desde el request con un valor por defecto si no existe
        $nombre = $request->get('nombre', 'Producto');

        // Obtener la uri y el url de la solicitud
        $path = $request->path();
        $url = $request->url();

        // Proceso para obtener archivos mediante peticiones de formulario
        $llave = 'archivo';
        // Validar que el archivo exista
        if ($request->exists($llave)) {
            // Obtener la instancia del objeto archivo
            $archivo = $request->file($llave);
            // Definir la ruta en donde se guardará el archivo
            $rutaArchivo = public_path('storage/productos/');
            // Definir el nombre con el que se guardará el archivo
            $nombreArchivo = $llave . '_' . time() . '.' . $archivo->getClientOriginalExtension();
            // Mover el archivo a la carpeta definida anteriormente
            $archivo->move($rutaArchivo, $nombreArchivo);
            // Obtener la url pública del archivo
            $nombreArchivo = url('storage/productos/' . $nombreArchivo);
        } else {
            // URL pública del archivo si no existe
            $nombreArchivo = '';
        }

        // Preguntar si existe el parámentro dentro de la petición
        // Es similar a $request->has('precio', 0.0);
        // Sin embargo se usa si la valdiación implica algúna otra lógica de negocio en caso de no existir
        if ($request->has('precio')) {
            $precio = $request->get('precio');
        } else {
            $precio = 0.0;
        }

        // Obtener el método en el que se invocó la solicitud y escribir lógica de negocio dependiendo del método
        $metodo = $request->method();
        /*switch($metodo){
          case 'post':
            $producto = new Producto();
            break;
          case 'put':
            $producto = Producto::find($id);
            break;
          default:
            $error = 'Metodo inválido';
            break;
        }*/

        // Obtener todos los parámetros en un arreglo
        $parametros = $request->all();

        return response()->json([
            'nombre' => $nombre,
            'precio' => $precio,
            'metodo' => $metodo,
            'path' => $path,
            'url' => $url,
            'nombreArchivo' => $nombreArchivo,
            'parametros' => $parametros
        ]);
    }
}
