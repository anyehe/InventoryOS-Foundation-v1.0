<?php
namespace Database\Seeders;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder {
    public function run(): void {
        $permissions=[
            'dashboard.view'=>'View dashboard','inventory.view'=>'View inventory','inventory.manage'=>'Manage inventory',
            'sales.view'=>'View sales','sales.manage'=>'Manage sales','purchases.view'=>'View purchases','purchases.manage'=>'Manage purchases',
            'reports.view'=>'View reports','reports.export'=>'Export reports','customers.view'=>'View customers','customers.manage'=>'Manage customers','returns.view'=>'View returns','returns.manage'=>'Manage returns','expenses.view'=>'View expenses','expenses.manage'=>'Manage expenses','users.manage'=>'Manage users','api_keys.manage'=>'Manage API keys','audit.view'=>'View audit logs',
        ];
        foreach($permissions as $name=>$label) Permission::updateOrCreate(['name'=>$name],['label'=>$label]);
        $roles=[
            'admin'=>['label'=>'Administrator','permissions'=>array_keys($permissions)],
            'manager'=>['label'=>'Manager','permissions'=>['dashboard.view','inventory.view','inventory.manage','sales.view','sales.manage','purchases.view','purchases.manage','reports.view','reports.export','customers.view','customers.manage','returns.view','returns.manage','expenses.view','expenses.manage']],
            'staff'=>['label'=>'Staff','permissions'=>['dashboard.view','inventory.view','sales.view','sales.manage','customers.view']],
        ];
        foreach($roles as $name=>$data){ $role=Role::updateOrCreate(['name'=>$name],['label'=>$data['label']]); $role->permissions()->sync(Permission::whereIn('name',$data['permissions'])->pluck('id')); }
        foreach ([['name'=>'Beverages','description'=>'Drinks and coffee products'],['name'=>'Packaging','description'=>'Cups, bags and consumables'],['name'=>'Ingredients','description'=>'Raw ingredients']] as $row) \App\Models\Category::updateOrCreate(['name'=>$row['name']],$row);
        foreach (['House Blend','Generic Supply','Northstar'] as $name) \App\Models\Brand::updateOrCreate(['name'=>$name],['is_active'=>true]);
        foreach ([['name'=>'Piece','short_name'=>'pc','conversion_rate'=>1],['name'=>'Box','short_name'=>'box','conversion_rate'=>12],['name'=>'Kilogram','short_name'=>'kg','conversion_rate'=>1]] as $row) \App\Models\Unit::updateOrCreate(['short_name'=>$row['short_name']],$row);
        foreach ([['name'=>'Main Warehouse','code'=>'MAIN','location'=>'Primary stock location'],['name'=>'Retail Branch','code'=>'BRANCH-01','location'=>'Retail location']] as $row) \App\Models\Warehouse::updateOrCreate(['code'=>$row['code']],$row);

        foreach ([['name'=>'Walk-in Customer','code'=>'CUST-000','email'=>null,'phone'=>null,'address'=>null,'credit_limit'=>0],['name'=>'Acme Cafe','code'=>'CUST-001','email'=>'hello@acme.test','phone'=>'+63 900 000 0101','address'=>'Local business account','credit_limit'=>50000]] as $row) \App\Models\Customer::updateOrCreate(['code'=>$row['code']],$row+['is_active'=>true]);
        foreach ([['name'=>'Utilities'],['name'=>'Transport'],['name'=>'Supplies'],['name'=>'Maintenance']] as $row) \App\Models\ExpenseCategory::updateOrCreate(['name'=>$row['name']],$row);

        foreach ([['name'=>'Northstar Wholesale','code'=>'SUP-001','email'=>'orders@northstar.test','phone'=>'+63 900 000 0001','address'=>'Cagayan de Oro distribution center'],['name'=>'Metro Supply Co.','code'=>'SUP-002','email'=>'sales@metrosupply.test','phone'=>'+63 900 000 0002','address'=>'Metro supplier account']] as $row) \App\Models\Supplier::updateOrCreate(['code'=>$row['code']],$row+['is_active'=>true]);

        $main=\App\Models\Warehouse::where('code','MAIN')->first();
        $branch=\App\Models\Warehouse::where('code','BRANCH-01')->first();
        $piece=\App\Models\Unit::where('short_name','pc')->first();
        $beverages=\App\Models\Category::where('name','Beverages')->first();
        $packaging=\App\Models\Category::where('name','Packaging')->first();
        $brand=\App\Models\Brand::where('name','House Blend')->first();
        $demoProducts=[
            ['name'=>'Arabica Coffee Beans','sku'=>'COF-001','category_id'=>$beverages->id,'brand_id'=>$brand->id,'unit_id'=>$piece->id,'cost_price'=>420,'selling_price'=>680,'reorder_level'=>10],
            ['name'=>'12oz Paper Cups','sku'=>'PKG-012','category_id'=>$packaging->id,'brand_id'=>null,'unit_id'=>$piece->id,'cost_price'=>210,'selling_price'=>320,'reorder_level'=>12],
            ['name'=>'Vanilla Syrup','sku'=>'SYR-009','category_id'=>$beverages->id,'brand_id'=>null,'unit_id'=>$piece->id,'cost_price'=>280,'selling_price'=>420,'reorder_level'=>10],
        ];
        foreach($demoProducts as $row){$product=\App\Models\Product::updateOrCreate(['sku'=>$row['sku']],$row); foreach([[$main,48],[$branch,7]] as [$warehouse,$qty]) \App\Models\WarehouseStock::updateOrCreate(['warehouse_id'=>$warehouse->id,'product_id'=>$product->id],['quantity'=>$qty]);}

        $admin=User::updateOrCreate(['email'=>'admin@inventory.test'],['name'=>'System Administrator','password'=>Hash::make('ChangeMe!123'),'role'=>'admin']);
        $admin->role_id=Role::where('name','admin')->value('id'); $admin->save();
    }
}
