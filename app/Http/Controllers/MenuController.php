<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\menu;
use App\Models\Submenus;
use App\Models\Role;
use App\Models\Permission;


class MenuController extends Controller
{
    public function index()
    {
        // $menus = Menu::all(); 
        $menus = Menu::orderBy('sort_order', 'asc')->get(); 
        return view('master.menu' ,compact('menus'));
    }
 
    public function store(Request $request)
    {
        $menu = new menu;
        $menu->menu_name = $request->input('menu_name');
        $menu->icon_class = $request->input('icon');
        $menu->show_menu = $request->input('status');
        $menu->routes = $request->input('route');
        $menu->sort_order = $request->input('sort_order', 0);
        $menu->save();

        return redirect()->back()->with('Success', 'Menu Succesfully Created');
    }

    public function destroy(Menu $menu)
    {
        if ($menu->delete()) {
            return redirect()->route('menus')->with('success', 'Menu item deleted successfully');
        } else {
            return redirect()->route('menus')->with('error', 'Failed to delete menu item');
        }
    }
    
    public function update(Request $request, $id)
    {
        $menu = Menu::findOrFail($id);
    
        $menu->menu_name = $request->input('menu_name');
        $menu->routes = $request->input('route');
        $menu->icon_class = $request->input('icon');
        $menu->show_menu = $request->input('status');
        $menu->sort_order = $request->input('sort_order', 0);
        $menu->save();
    
        return redirect()->back()->with('success', 'Menu Updated Successfully');
    }


    public function submenu() {

        // $data['submenus'] = Submenus::with('menu')->get()->toArray();
        // $data['parent'] = Menu::all()->toArray();
        // echo'<pre>'; print_r($data['submenus']); exit;
        $data['submenus'] = Submenus::with('menu')
        ->orderBy('menus_id', 'asc')
        ->orderBy('sort_order', 'asc')
        ->get()
        ->toArray();

    $data['parent'] = Menu::orderBy('sort_order', 'asc')->get()->toArray();
        
        return view('master.submenus', compact('data'));
    }

public function delete(Submenus $submenus)
{
    if ($submenus->delete()) {
        return redirect()->back()->with('success', 'Menu Delete Successfully');
    } else {
        return redirect()>back()->with('error', 'Failed to delete menu item');
    }
}

public function submenu_insert(Request $request)
{
    $submenus = new Submenus;
    $submenus->sub_menu_name = $request->input('menu_name');
    $submenus->routes = $request->input('route');
    $submenus->menus_id = $request->input('parent');
    $submenus->show = $request->input('status');
    $submenus->sort_order = $request->input('sort_order', 0);
    $submenus->save();

    return redirect()->back()->with('success', 'Menu Added Successfully');
}

public function submenu_update(Request $request, $id)
{
    try {
        // Find the Submenus instance by its ID
        $submenus = Submenus::findOrFail($id);

        // Update the Submenus instance with the validated data
        $submenus->sub_menu_name = $request->input('menu_name');
        $submenus->routes = $request->input('route');
        $submenus->menus_id = $request->input('parent');
        $submenus->show = $request->input('status');
        $submenus->sort_order = $request->input('sort_order', 0);
        $submenus->save();

        return redirect()->back()->with('success', 'Menu Updated Successfully');
    } catch (\Exception $e) {
        // Handle exceptions, such as not finding the record
        return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
    }
}

public function userright()
{
    $roles = Role::all(); 
    return view('master.userright', compact('roles')); 
}

public function permission(Request $req)
{
    
    $role = $req->input('role_id');
    $menu_id = json_encode($req->input('menu_id'));
    $submenu_id = json_encode($req->input('submenu_id'));

    Permission::updateOrCreate(
        ['role_id' => $role],
        ['menu_id' => $menu_id, 'submenu_id' => $submenu_id]
    );
    return redirect()->back()->with('success', 'Premission Granted Successfully');

}
function rolePermission(Request $request){
    $role_id = $request->input('role_id');
    $permission = Permission::where('role_id', $role_id)->first();

    if ($permission) {
        $menuid = json_decode($permission->menu_id);
        $submenuid = json_decode($permission->submenu_id);
        $data = [
            'menuid' => $menuid,
            'submenuid' => $submenuid,
        ];
        return response()->json($data);
    } else {
        // Handle the case where no permission is found for the given role_id
        return response()->json(['error' => 'No permission found for the given role_id'], 404);
    }
}


    
    
}
