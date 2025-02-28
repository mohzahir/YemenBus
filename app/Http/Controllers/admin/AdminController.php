<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Competition;
use App\Participant;
use App\Admin;
use App\Marketer;
use App\Provider;
use App\Setting;
use DB;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $total_participants = Participant::count();
        $total_competitions = Competition::count();
        // $total_participants =5;
        // $total_competitions =competition::count();;
        $total_provider = Provider::count();
        $total_marketer = Marketer::count();
        $total_reservation_confirm =  DB::table('reseervations')->count();
        //$total_reservation_delay = Marketer::count();
        //$total_reservation_cancel = Marketer::count();

        return view('dashboard.index', [
            'total_participants' => $total_participants,
            'total_competitions' => $total_competitions,
            'total_provider' => $total_provider,
            'total_marketer' => $total_marketer,
            'total_reservation_confirm' => $total_reservation_confirm,

        ]);
    }

    public function admin()
    {
        $admin = Admin::find(1);
        return view('dashboard.admin', [
            'admin' => $admin
        ]);
    }

    public function update()
    {
        $attibutes = request()->validate([
            'name' => 'required',
            'email' => ['string', 'email', 'required'],
            'password' => ['string', 'required', 'confirmed']
        ]);
        if ($attibutes['password']) {
            $attibutes['password'] = bcrypt(request('password'));
        }
        $admin = Admin::find(1);
        $admin->update($attibutes);

        return back()->with('message', 'تم تحديث البيانات')->with('type', 'success');
    }

    public function search(Request $request)
    {
        if ($request->ajax()) {
            $output = "";
            $reseervations = DB::table('reseervations')->where('order_id', 'LIKE', '%' . $request->search . "%")->get();
            if ($reseervations) {
                foreach ($reseervations as $key => $reseervation) {
                    $output .= '<tr>' .
                        '<td>' . $reseervation->order_id . '</td>' .
                        '<td>' . $reseervation->order_url . '</td>' .
                        '<td>' . $reseervation->passenger_phone . '</td>' .
                        '<td>' . $reseervation->code . '</td>' .
                        '<td>' . $reseervation->amount . '</td>' .
                        '<td>' . $reseervation->amount_deposit . '</td>' .
                        '<td>' . $reseervation->date . '</td>' .
                        '</tr>';
                }
                return Response($output);
            }
        }
    }

    public function generalSettings()
    {
        return view('dashboard.general-settings');
    }

    public function storeGeneralSettings(Request $request)
    {
        $validatedData = $request->validate([
            'BUS_RS_DEPOSIT_VALUE' => 'required|numeric',
            'BUS_RY_DEPOSIT_VALUE' => 'required|numeric',
            'HAJ_PROGRAM_RS_DEPOSIT' => 'required|numeric',
            'OMRA_PROGRAM_RS_DEPOSIT' => 'required|numeric',
            'HAJ_SERVICE_RS_PRICE' => 'required|numeric',
            'OMRA_SERVICE_RS_PRICE' => 'required|numeric',
            'BANK_ACCOUNT' => 'required|numeric',
            'BANK_TRANSFER_SERVICE_DESCRIPTION' => 'required|string',
            'ON_DELIVERY_PAYMENT_SERVICE_DESCRIPTION' => 'required|string',
            'PAYMENT_WITH_FRIEND_SERVICE_DESCRIPTION' => 'required|string',
            'PRIVACY_PAGE_CONTENT' => 'required|string',
        ]);

        Setting::create($validatedData);

        return redirect()->back()->with(['success' => 'تم تحديث البيانات بنجاح']);
    }
}
