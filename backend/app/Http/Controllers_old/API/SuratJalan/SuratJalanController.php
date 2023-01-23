<?php

namespace App\Http\Controllers\API\SuratJalan;

use App\Http\Controllers\Controller;
use App\Models\API\SuratJalan\AppSuratJalan;
use App\Models\API\SuratJalan\AppSuratJalanDetail;
use App\Models\API\SuratJalan\AppSuratJalanLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\API\SuratJalanExport;

class SuratJalanController extends Controller
{

  public function handleSourceData()
  {
    $cari = request()->q;
    $data = AppSuratJalan::orderBy(request()->sortby, request()->sortbydesc)
        ->whereHas('detail', function($query) use ($cari){
          $query->where(function ($query) use ($cari){
            $query->where('detail_customer', 'LIKE', '%'.$cari.'%')
                ->orWhere('detail_address', 'LIKE', '%'.$cari.'%')
                ->orWhere('detail_city', 'LIKE', '%'.$cari.'%')
                ->orWhere('detail_nopol', 'LIKE', '%'.$cari.'%')
                ->orWhere('detail_driver', 'LIKE', '%'.$cari.'%')
                ->orWhere('detail_item', 'LIKE', '%'.$cari.'%')
                ->orWhere('detail_qty', 'LIKE', '%'.$cari.'%')
                ->orWhere('detail_uom', 'LIKE', '%'.$cari.'%')
                ->orWhere('detail_sending_date', 'LIKE', '%'.$cari.'%');
          })
          ->orWhere('delivery_no', 'LIKE', '%'.$cari.'%')
          ->orWhere('do_no', 'LIKE', '%'.$cari.'%')
          ->orWhere('po_no', 'LIKE', '%'.$cari.'%')
          ->orWhere('invoice_no', 'LIKE', '%'.$cari.'%');
        })
        ->orWhereHas('creator', function($query) use ($cari){
          $query->where('name', 'LIKE', '%'.$cari.'%');
        })
        ->with('detail', 'creator')
        ->paginate(request()->per_page);

    return response()->json(['message' => $data], 200);
  }

  public function handlePostInvoice(Request $request)
  {
    $invoice = AppSuratJalan::find($request->input('deliveryno'));
    $invoice->invoice_no = $request->input('invoiceno');
    $invoice->is_processing = '0';
    $invoice->is_remark = '1';
    $invoice->save();

    if(!$invoice) { return response()->json(['message' => 'Gagal update invoice.'], 500); }
    return response()->json(['message' => 'Invoiced'], 200);
  }

  public function handlePrintCounting(Request $request)
  {
    $count = AppSuratJalan::find($request->input('deliveryno'));
    $count->print_count = $count->print_count +1;
    $count->save();

    if(!$count) { return response()->json(['message' => 'Gagal update print count.'], 500); }
    return response()->json(['message' => 'Print Counted +1.'], 200);
  }

  public function handleNumber()
  {
    $base = 'S-'.Carbon::now()->format('ymd');
    if(AppSuratJalan::where('delivery_no', 'LIKE', $base.'%')->count()>0){
      $lastno = AppSuratJalan::orderBy('delivery_no', 'desc')->first();
      $count = substr($lastno->delivery_no, 2)+1;
      $delivery_no = 'S-'.$count; }
    else{ $delivery_no = $base.'0001'; }
    return $delivery_no;
  }

  public function handleStore(Request $request)
  {
    $this->validate($request, [
      'customernama' => 'required',
      'customeralamat' => 'required',
      'customerkota' => 'required',
      'nopol' => 'required',
      'drivernama' => 'required',
      'item' => 'required',
      'qty' => 'required',
      'uom' => 'required',
      'tanggalkirim' => 'required',
    ],[
      'customernama.required' => 'Customer tidak boleh kosong.',
      'customeralamat.required' => 'Alamat pengiriman tidak boleh kosong.',
      'customerkota.required' => 'Kota tujuan tidak boleh kosong.',
      'nopol.required' => 'Nomor pengemudi tidak boleh kosong.',
      'drivernama.required' => 'Nama pengemudi tidak boleh kosong.',
      'item.required' => 'Piih item.',
      'qty.required' => 'Kuantiti tidak boleh kosong.',
      'uom.required' => 'Pilih UOM.',
      'tanggalkirim.required' => 'Tanggal pengiriman tidak boleh kosong.',
    ]);
    
    $base = 'S-'.Carbon::now()->format('ymd');
    if(AppSuratJalan::where('delivery_no', 'LIKE', $base.'%')->count()>0){
      $lastno = AppSuratJalan::orderBy('delivery_no', 'desc')->first();
      $count = substr($lastno->delivery_no, 2)+1;
      $delivery_no = 'S-'.$count; }
    else{ $delivery_no = $base.'0001'; }

    $user = Auth::guard('sanctum')->user()->detail;

    if($request->input('btnRequest') == 1){ $desc = 'Create and Print Surat Jalan'; }
    else{ $desc = 'Create Surat Jalan'; }

    DB::beginTransaction();
      $store = new AppSuratJalan;
      // $store->delivery_no = $request->input('dnote');
      $store->delivery_no = $delivery_no;
      $store->do_no = $request->input('donumber');
      $store->po_no = $request->input('ponumber');
      $store->created_by = $user->id;
      $store->is_processing = '1';
      $store->is_remark = '0';
      $store->save();

      $storedetail = new AppSuratJalanDetail;
      // $storedetail->deliveryno_id = $request->input('dnote');
      $storedetail->deliveryno_id = $delivery_no;
      $storedetail->detail_customer = ucfirst($request->input('customernama'));
      $storedetail->detail_address = ucfirst($request->input('customeralamat'));
      $storedetail->detail_city = strtoupper($request->input('customerkota'));
      $storedetail->detail_nopol = strtoupper($request->input('nopol'));
      $storedetail->detail_driver = strtoupper($request->input('drivernama'));
      $storedetail->detail_item = $request->input('item');
      $storedetail->detail_qty = $request->input('qty');
      $storedetail->detail_uom = $request->input('uom');
      $storedetail->detail_sending_date = $request->input('tanggalkirim');
      $storedetail->save();

      $storelog = new AppSuratJalanLog;
      // $storelog->deliveryno_id = $request->input('dnote');
      $storelog->deliveryno_id = $delivery_no;
      $storelog->action_name = $desc;
      $storelog->created_by = $user->id;
      $storelog->save();
    DB::commit();

    // $store = AppSuratJalan::find($delivery_no)->load('detail');

    // if(!$store || !$storelog || !$storedetail){
    //   return response()->json(['message' => 'Surat Jalan gagal disimpan.'], 500);
    // }
    return response()->json([
      'message' => 'Surat Jalan berhasil disimpan.',
      'result' => $store,
    ], 200);
  }

  public function handleShowData($delivery)
  {
    return AppSuratJalan::find($delivery)->load('detail');
  }

  public function handleUpdate(Request $request, $delivery)
  {
    $user = Auth::guard('sanctum')->user()->detail;
    if($request->input('btnRequest') == 1){ $desc = 'Update and Print Surat Jalan'; }
    else{ $desc = 'Update Surat Jalan'; }

    DB::beginTransaction();
      $update = AppSuratJalan::find($request->input('dnote'));
      $update->do_no = $request->input('donumber');
      $update->po_no = $request->input('ponumber');
      $update->save();

      $updatedetail = AppSuratJalanDetail::find($request->input('dnote'));
      $updatedetail->detail_customer = ucfirst($request->input('customernama'));
      $updatedetail->detail_address = ucfirst($request->input('customeralamat'));
      $updatedetail->detail_city = strtoupper($request->input('customerkota'));
      $updatedetail->detail_nopol = strtoupper($request->input('nopol'));
      $updatedetail->detail_driver = strtoupper($request->input('drivernama'));
      $updatedetail->detail_item = $request->input('item');
      $updatedetail->detail_qty = $request->input('qty');
      $updatedetail->detail_uom = $request->input('uom');
      $updatedetail->detail_sending_date = $request->input('tanggalkirim');
      $updatedetail->save();

      $updatelog = new AppSuratJalanLog;
      $updatelog->deliveryno_id = $request->input('dnote');
      $updatelog->action_name = $desc;
      $updatelog->created_by = $user->id;
      $updatelog->save();
    DB::commit();

    $updated = AppSuratJalan::find($request->input('dnote'))->load('detail');

    if(!$update || !$updatelog || !$updatedetail){
        return response()->json(['message' => 'Surat Jalan gagal disimpan.'], 500);
      }
    return response()->json([
      'message' => 'Surat Jalan berhasil disimpan.',
      'result' => $updated
    ], 200);
  }
}
