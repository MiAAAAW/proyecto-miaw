<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class CategoryController extends Controller
{
    //

    public function AllCategory()
    {
        //latest() method is used to order query results by the created_at timestamp in descending order
        $category = Category::latest()->get();
        return view('admin.backend.category.all_category', compact('category'));
    }

    public function AddCategory()
    {
        return view('admin.backend.category.add_category');
    }

    public function StoreCategory(Request $request)
    {
        // Validar los datos recibidos
        $request->validate([
            'category_name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        $save_url = null;

        if ($request->hasFile('image')) {
            try {
                // Obtener el archivo de imagen
                $image = $request->file('image');

                // Generar un nombre único para la imagen
                $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();

                // Mover la imagen a la carpeta pública
                $path = $image->move(public_path('upload/category'), $name_gen);

                // Verificar si el archivo se movió correctamente
                if ($path) {
                    $save_url = 'upload/category/' . $name_gen;
                } else {
                    dd('Error al mover la imagen.');
                }
            } catch (\Exception $e) {
                dd('Error: ' . $e->getMessage());
            }
        }

        // Intentar crear la categoría
        try {
            $category = Category::create([
                'category_name' => $request->category_name,
                'category_slug' => strtolower(str_replace(' ', '-', $request->category_name)),
                'image' => $save_url
            ]);

            if ($category) {
                return redirect()->route('all.category')->with('success', 'Category Inserted Successfully');
            } else {
                dd('Error al crear la categoría.');
            }
        } catch (\Exception $e) {
            dd('Error al crear la categoría: ' . $e->getMessage());
        }
    }

    public function EditCategory($id)
    {
        $category = Category::find($id);
        return view('admin.backend.category.edit_category', compact('category'));
    }

    public function UpdateCategory(Request $request)
    {
        $category_id = $request->get('id');
        if ($request->file('image')) {
            $item = Category::find($category_id);
            if (!empty($item->image)) {
                unlink($item->image);
            }
            $image = $request->file('image');
            // create image manager with desired driver
            $manager = new ImageManager(new Driver());
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();

            $img = $manager->read($image);
            $img = $img->resize(370, 246);
            $img->toJpeg(80)->save(base_path('public/upload/category/' . $name_gen));
            $save_url = 'upload/category/' . $name_gen;
            Category::find($category_id)->update([
                'category_name' => $request->get('category_name'),
                'category_slug' => strtolower(str_replace(' ', '-', $request->get('category_name'))),
                'image' => $save_url
            ]);

            $notification = array(
                'message' => "Category Updated with image Successfully",
                'alert-type' => 'success'
            );
            return redirect()->route('all.category')->with($notification);
        } else {
            Category::find($category_id)->update([
                'category_name' => $request->get('category_name'),
                'category_slug' => strtolower(str_replace(' ', '-', $request->get('category_name'))),
            ]);

            $notification = array(
                'message' => "Category Updated with image Failed",
                'alert-type' => 'success'
            );
            return redirect()->route('all.category')->with($notification);
        }
    }

    public function DeleteCategory($id)
    {
        $item = Category::find($id);
        $img = $item->image;
        unlink($img);

        Category::find($id)->delete();
        $notification = array(
            'message' => "Category Deleted Successfully",
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }

    public function AllSubCategory()
    {
        $subcategory = SubCategory::latest()->get();
        return view('admin.backend.subcategory.all_subcategory', compact('subcategory'));
    }

    public function AddSubCategory()
    {
        $category = Category::latest()->get();
        return view('admin.backend.subcategory.add_subcategory', compact('category'));
    }

    public function StoreSubCategory(Request $request)
    {
        SubCategory::insert([
            'category_id' => $request->get('category_id'),
            'subcategory_name' => $request->get('subcategory_name'),
            'subcategory_slug' => strtolower(str_replace(' ', '-', $request->get('subcategory_name'))),
        ]);

        $notification = array(
            'message' => "Subcategory Inserted Successfully",
            'alert-type' => 'success'
        );
        return redirect()->route('all.subcategory')->with($notification);
    }

    public function EditSubCategory($id)
    {
        $category = Category::latest()->get();
        $subcategory = SubCategory::find($id);
        return view('admin.backend.subcategory.edit_subcategory', compact('category', 'subcategory'));
    }

    public function UpdateSubCategory(Request $request)
    {
        $subCat_id = $request->get('id');
        SubCategory::find($subCat_id)->update([
            'category_id' => $request->get('category_id'),
            'subcategory_name' => $request->get('subcategory_name'),
            'subcategory_slug' => strtolower(str_replace(' ', '-', $request->get('subcategory_name'))),
        ]);

        $notification = array(
            'message' => "Subcategory Updated Successfully",
            'alert-type' => 'success'
        );
        return redirect()->route('all.subcategory')->with($notification);
    }

    public function DeleteSubCategory($id)
    {
        SubCategory::find($id)->delete();
        $notification = array(
            'message' => "Subcategory Deleted Successfully",
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }
}
