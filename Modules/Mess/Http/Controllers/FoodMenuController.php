<?php

namespace Modules\Mess\Http\Controllers;

use App\Http\Controllers\Helpers\AcademicHelper;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Modules\Inventory\Entities\StockUOM;
use Modules\Mess\Entities\FoodMenu;
use Modules\Mess\Entities\FoodMenuCategory;
use Modules\Mess\Entities\FoodMenuItem;
use Modules\Mess\Http\Requests\FoodMenuCategoryRequest;
use Modules\Mess\Http\Requests\FoodMenuItemRequest;
use Modules\Mess\Http\Requests\FoodMenuRequest;

class FoodMenuController extends Controller
{
    private $academicHelper;

    public function __construct(AcademicHelper $academicHelper)
    {
        $this->academicHelper = $academicHelper;
    }



    public function index()
    {
        $foodMenuCategories = FoodMenuCategory::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute()
        ])->get();

        $foodMenus = FoodMenu::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute()
        ])->get();

        $uoms = StockUOM::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute()
        ])->get();

        $foodMenuItems = FoodMenuItem::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute()
        ])->get();

        return view('mess::food-menu.index', compact('foodMenuCategories', 'foodMenus', 'uoms', 'foodMenuItems'));
    }



    public function create()
    {
        return view('mess::create');
    }



    public function store(FoodMenuCategoryRequest $request)
    {
        $sameNameCategory = FoodMenuCategory::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'category_name' => $request->categoryName
        ])->get();

        if (sizeOf($sameNameCategory) > 0) {
            Session::flash('errorMessage', 'Sorry! There is already a category in this name.');
            return redirect()->back();
        }

        DB::beginTransaction();
        try {
            $insertCategory = FoodMenuCategory::insert([
                'category_name' => $request->categoryName,
                'created_at' => Carbon::now(),
                'created_by' => Auth::id(),
                'campus_id' => $this->academicHelper->getCampus(),
                'institute_id' => $this->academicHelper->getInstitute()
            ]);

            if ($insertCategory) {
                DB::commit();
                Session::flash('message', 'Food menu category created successfully.');
                return redirect()->back();
            } else {
                Session::flash('errorMessage', 'Error creating food menu category.');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 'Error creating food menu category.');
            return redirect()->back();
        }
    }



    public function show($id)
    {
        return view('mess::show');
    }



    public function edit($id)
    {
        $foodMenuCategory = FoodMenuCategory::findOrFail($id);

        return view('mess::food-menu.modal.edit-menu-category', compact('foodMenuCategory'));
    }



    public function update(FoodMenuCategoryRequest $request, $id)
    {
        $foodMenuCategory = FoodMenuCategory::findOrFail($id);

        $sameNameCategory = FoodMenuCategory::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'category_name' => $request->categoryName
        ])->first();

        if ($sameNameCategory) {
            if ($sameNameCategory->id != $foodMenuCategory->id) {
                Session::flash('errorMessage', 'Sorry! There is already a menu category in this name.');
                return redirect()->back();
            }
        }

        DB::beginTransaction();
        try {
            $updateCategory = $foodMenuCategory->update([
                'category_name' => $request->categoryName,
                'updated_at' => Carbon::now(),
                'updated_by' => Auth::id()
            ]);

            if ($updateCategory) {
                DB::commit();
                Session::flash('message', 'Food menu category updated successfully.');
                return redirect()->back();
            } else {
                Session::flash('errorMessage', 'Error updating food menu category.');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 'Error updating food menu category.');
            return redirect()->back();
        }
    }



    public function destroy($id)
    {
        $foodMenu = FoodMenu::where('category_id', $id)->first();
        if ($foodMenu) {
            Session::flash('errorMessage', 'Can not deleted! Food menu exist with this category.');
            return redirect()->back();
        } else {
            $foodMenuCategory = FoodMenuCategory::findOrFail($id);
            $foodMenuCategory->delete();
            Session::flash('message', 'Food menu category deleted successfully.');
            return redirect()->back();
        }
    }

    public function storeMenu(FoodMenuRequest $request)
    {
        $sameNameMenu = FoodMenu::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'menu_name' => $request->menuName
        ])->get();

        if (sizeOf($sameNameMenu) > 0) {
            Session::flash('errorMessage', 'Sorry! There is already a menu in this name.');
            return redirect()->back();
        }

        DB::beginTransaction();
        try {
            $insertMenu = FoodMenu::insert([
                'category_id' => $request->categoryId,
                'menu_name' => $request->menuName,
                'created_at' => Carbon::now(),
                'created_by' => Auth::id(),
                'campus_id' => $this->academicHelper->getCampus(),
                'institute_id' => $this->academicHelper->getInstitute()
            ]);

            if ($insertMenu) {
                DB::commit();
                Session::flash('message', 'Food menu created successfully.');
                return redirect()->back();
            } else {
                Session::flash('errorMessage', 'Error creating food menu.');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 'Error creating food menu.');
            return redirect()->back();
        }
    }

    public function storeMenuItem(FoodMenuItemRequest $request)
    {
        $sameNameMenuItem = FoodMenuItem::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'item_name' => $request->itemName
        ])->get();

        if (sizeOf($sameNameMenuItem) > 0) {
            Session::flash('errorMessage', 'Sorry! There is already a menu item in this name.');
            return redirect()->back();
        }

        DB::beginTransaction();
        try {
            $insertMenuItem = FoodMenuItem::insert([
                'item_name' => $request->itemName,
                'uom_id' => $request->uomId,
                'value' => $request->value,
                'cost' => $request->cost,
                'created_at' => Carbon::now(),
                'created_by' => Auth::id(),
                'campus_id' => $this->academicHelper->getCampus(),
                'institute_id' => $this->academicHelper->getInstitute()
            ]);

            if ($insertMenuItem) {
                DB::commit();
                Session::flash('message', 'Food menu item created successfully.');
                return redirect()->back();
            } else {
                Session::flash('errorMessage', 'Error creating food menu item.');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 'Error creating food menu item.');
            return redirect()->back();
        }
    }


    public function menuEdit($id)
    {
        $foodMenu = FoodMenu::findOrFail($id);
        $foodMenuCategories = FoodMenuCategory::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute()
        ])->get();

        return view('mess::food-menu.modal.edit-menu', compact('foodMenu', 'foodMenuCategories'));
    }

    public function menuItemEdit($id)
    {
        $foodMenuItem = FoodMenuItem::findOrFail($id);
        $uoms = StockUOM::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute()
        ])->get();

        return view('mess::food-menu.modal.edit-menu-item', compact('foodMenuItem', 'uoms'));
    }

    public function updateMenu(FoodMenuRequest $request, $id)
    {
        $foodMenu = FoodMenu::findOrFail($id);

        $sameNameMenu = FoodMenu::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'menu_name' => $request->menuName
        ])->first();

        if ($sameNameMenu) {
            if ($sameNameMenu->id != $foodMenu->id) {
                Session::flash('errorMessage', 'Sorry! There is already a menu in this name.');
                return redirect()->back();
            }
        }

        DB::beginTransaction();
        try {
            $updateMenu = $foodMenu->update([
                'category_id' => $request->categoryId,
                'menu_name' => $request->menuName,
                'updated_at' => Carbon::now(),
                'updated_by' => Auth::id()
            ]);

            if ($updateMenu) {
                DB::commit();
                Session::flash('message', 'Food menu updated successfully.');
                return redirect()->back();
            } else {
                Session::flash('errorMessage', 'Error updating food menu.');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 'Error updating food menu.');
            return redirect()->back();
        }
    }

    public function updateMenuItem(FoodMenuItemRequest $request, $id)
    {
        $foodMenuItem = FoodMenuItem::findOrFail($id);

        $sameNameMenuItem = FoodMenuItem::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'item_name' => $request->itemName
        ])->first();

        if ($sameNameMenuItem) {
            if ($sameNameMenuItem->id != $foodMenuItem->id) {
                Session::flash('errorMessage', 'Sorry! There is already a menu item in this name.');
                return redirect()->back();
            }
        }

        DB::beginTransaction();
        try {
            $updateMenuItem = $foodMenuItem->update([
                'item_name' => $request->itemName,
                'uom_id' => $request->uomId,
                'value' => $request->value,
                'cost' => $request->cost,
                'updated_at' => Carbon::now(),
                'updated_by' => Auth::id()
            ]);

            if ($updateMenuItem) {
                DB::commit();
                Session::flash('message', 'Food menu item update successfully.');
                return redirect()->back();
            } else {
                Session::flash('errorMessage', 'Error updating food menu item.');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 'Error updating food menu item.');
            return redirect()->back();
        }
    }

    public function assignItemView($id)
    {
        $foodMenu = FoodMenu::findOrFail($id);
        $foodMenuItems = FoodMenuItem::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute()
        ])->get();
        $uoms = StockUOM::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute()
        ])->get();

        $menuItemIds = [];
        $menuItemQty = [];

        if ($foodMenu->menu_items) {
            foreach (json_decode($foodMenu->menu_items, 1) as $key => $item) {
                array_push($menuItemIds, $item['id']);
                array_push($menuItemQty, $item['qty']);
            }
        }

        return view('mess::food-menu.modal.assign-item', compact('foodMenu', 'foodMenuItems', 'menuItemIds', 'menuItemQty', 'uoms'));
    }

    public function assignItemToMenu(Request $request, $id)
    {
        $foodMenu = FoodMenu::findOrFail($id);
        $menuItems = null;

        if ($request->items) {
            $menuItems = [];
            foreach ($request->items as $key => $item) {
                $myArray = ['id' => $item, 'qty' => $request->itemQty[$key]];
                array_push($menuItems, $myArray);
            }
        }

        DB::beginTransaction();
        try {
            $updateFoodMenu = $foodMenu->update([
                'menu_items' => ($menuItems) ? json_encode($menuItems) : null,
                'updated_at' => Carbon::now(),
                'updated_by' => Auth::id()
            ]);

            if ($updateFoodMenu) {
                DB::commit();
                Session::flash('message', 'Menu Items assigned successfully.');
                return redirect()->back();
            } else {
                Session::flash('errorMessage', 'Error assigning menu items.');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 'Error assigning menu items.');
            return redirect()->back();
        }
    }
}
