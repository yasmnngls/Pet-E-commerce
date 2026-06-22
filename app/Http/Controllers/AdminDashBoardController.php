<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\SellerApplication;
use App\Models\User;
use Illuminate\Http\Request;

class AdminDashBoardController extends Controller
{
    //Fetch Data
    public function index(){
        //Fetch Data
        $users = User::all();
        $products = Product::where('status', 'approved')->get();

        //Fetch queue for approval
        $pendingSellers = SellerApplication::where('status', 'pending')->get();
        $pendingProducts = Product::where('status', 'pending')->get();

        return view('admin.dashboard', compact('users', 'products', 'pendingSellers', 'pendingProducts'));
    }

    //CRUD: Update(Change Role of User)
    public function updateUserRole(Request $request, $id){
        $user = User::findOrFail($id);
        $user->update(['role'=>$request->role]);
        return back()->with('success', "User role updated to {$request->role} successfully.");
    }

    //CRUD: Delete(Remove User) 
    public function deleteUser($id){
        User::findOrFail($id)->delete();
        return back()->with('success', 'User account successfully removed.');
    }

    public function deleteProduct($id){
        Product::findOrFail($id)->delete();
        return back()->with('success', 'Product listing removed from marketplace.');
    }

    //Approval System (Seller)
    public function approveSeller($id){
        $application = SellerApplication::findOrFail($id);
        $application->update(['status'=>'approved']);

        $user = User::find($application->user_id);
        if($user){
            $user->update(['role'=>'seller']);
        }

        return back()->with('success', 'Seller application approved. Vendor status activated!');
    }

    public function rejectSeller($id){
        SellerApplication::findOrFail($id)->update(['status'=>'rejected']);
        return back()->with('success', 'Seller application rejected.');
    }

    //Approval System(Product)
    public function approveProduct($id){
        Product::findOrFail($id)->update(['status'=>'approved']);
        return back()->with('success', 'Product listing has been approved and published to the shop floor.');
    }

    public function rejectProduct($id){
        Product::findOrFail($id)->update(['status'=>'rejected']);
        return back()->with('success', 'Product listing rejected.');
    }
}
