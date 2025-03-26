<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Producto;

class MenuController extends ProductoBaseController
{
    public function create_get()
    {
        return view('menu.create');
    }

    public function create_post(Request $request)
    {
        $descripcion = $request->descripcion;
        $cod = $request->cod;

        $menu = new Menu();
        $menu->cod = $cod;
        $menu->descripcion = $descripcion;
        $menu->save();

        return redirect()->route('menus.paginate')
            ->with('success', 'Menú creado correctamente');
    }

    public function show_get($cod)
    {   
        try {
            $menu = Menu::where('cod', $cod)->firstOrFail();
            return view('menu.profile', ['menu' => $menu]);
        } catch (ModelNotFoundException $e) {
            return response()->json(["message" => "Menu cod = $cod not found"], 404);
        }
    }

    public function show_post(Request $request)
    {
        $cod = $request->cod;
        try {
            $menu = Menu::where('cod', $cod)->firstOrFail();
            return view('menu.profile', ['menu' => $menu]);
        } catch (ModelNotFoundException $e) {
            return response()->json(["message" => "Menu cod = $cod not found"], 404);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = $request->validate([
                'descripcion' => 'required',
                'cod' => 'required|unique:menus,cod',
            ]);

            $menu = new Menu();
            $menu->cod = $request->cod;
            $menu->descripcion = $request->descripcion;
            $menu->save();

            return redirect()->route('menus.paginate')
                ->with('success', 'Menú creado correctamente');
            
        } catch (Exception $e) {
            return back()->withInput()
                ->with('error', 'Hubo un error al crear el menú: ' . $e->getMessage());
        }
    }

    public function paginate(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);
    
        $query = Menu::query();
    
        if ($search) {
            $query->where('descripcion', 'like', "%{$search}%");
        }
    
        $menus = $query->paginate($perPage);
    
        return view('menu.paginate', ['menus' => $menus]);
    }

    public function edit(Request $request, $cod)
    {
        try {
            $menu = Menu::where('cod', $cod)->firstOrFail();
            return view('menu.edit', ['menu' => $menu]);
        } catch (ModelNotFoundException $e) {
            return response()->json(["message" => "Menu cod = $cod not found"], 404);
        }
    }

    public function update(Request $request, $cod)
    {
        try {
            $menu = Menu::where('cod', $cod)->firstOrFail();
        
            $request->validate([
                'descripcion' => 'required',
            ]);

            $menu->descripcion = $request->descripcion;
            $menu->save();

            return redirect()->route('menus.paginate')
                ->with('success', 'Menú actualizado correctamente');
            
        } catch (ModelNotFoundException $e) {
            return redirect()->route('menus.paginate')
                ->with('error', 'Menú no encontrado');
        } catch (Exception $e) {
            return redirect()->route('menus.paginate')
                ->with('error', $e->getMessage());
        }
    }

    public function destroy($cod)
    {
        try {
            $menu = Menu::where('cod', $cod)->firstOrFail();
            $menu->delete();
        
            return redirect()->route('menus.paginate')
                ->with('success', 'Menú eliminado correctamente');
            
        } catch (ModelNotFoundException $e) {
            return redirect()->route('menus.paginate')
                ->with('error', 'Menú no encontrado');
        } catch (Exception $e) {
            return redirect()->route('menus.paginate')
                ->with('error', $e->getMessage());
        }
    }

    public function delete($cod)
    {
        return $this->destroy($cod);
    }


    public function search(Request $request)
    {
        $descripcion = $request->descripcion;
        $menus = Menu::where('descripcion', 'like', "%$descripcion%")->get();
        return view('menu.search', ['menus' => $menus]);
    }
}
