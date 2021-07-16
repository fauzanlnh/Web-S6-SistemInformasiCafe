<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Pemesanan;
class MenuController extends Controller
{
    //Customer
    //Menampilkan daftar menu minuman
    public function createMinuman(){
        $DetailController = new DetailPemesananController();
        $daftar_pesanan = $DetailController->getAllByIdPesanan();
        $minuman = $this->getMinuman();
        $datapesanan = $this->getIdPesanan();
        return view('Tamu/formtambahminuman', ['list_minuman' => $minuman, 'a' => $datapesanan, 'daftar_pesanan' => $daftar_pesanan]);
    }
    //Menampilkan daftar menu makanan
    public function createMakanan(){
        $DetailController = new DetailPemesananController();
        $daftar_pesanan = $DetailController->getAllByIdPesanan();
        $makanan = $this->getMakanan();
        $datapesanan = $this->getIdPesanan();
        return view('Tamu/formtambahmakanan', ['list_makanan' => $makanan, 'a' => $datapesanan, 'daftar_pesanan' => $daftar_pesanan]);
    }
    //Menampilkan detail menu
    public function detailMenu(){
        $DetailController = new DetailPemesananController();
        $daftar_pesanan = $DetailController->getAllByIdPesanan();
        $idmenu = $this->getIdMenu();
        $detail = $this->getMenuById($idmenu);
        $datapesanan =  $this->getIdPesanan();
        return view('Tamu/detailmenu', ['detail_menu' => $detail, 'a' => $datapesanan, 'daftar_pesanan' => $daftar_pesanan]);
    }
    //Mengambil Id Menu
    public function getIdMenu(){
        $currentURL = \URL::current();
        $tes = explode('/',$currentURL);
        return $tes[11];
    }
    //Mengambil Id Pesanan dari No Meja
    public function getNoMeja(){
        $currentURL = \URL::current();
        $tes = explode('/',$currentURL);
        return $tes[8];
    }
    public function getIdPesanan(){
        /*$pemesanan = Pemesanan::where('no_meja', $this->getNoMeja())
                ->get();*/
        $pemesanan = Pemesanan::select('*')
            ->where('no_meja', '=', $this->getNoMeja())
            ->where('status_pembayaran', '=', "Masih")
            ->get();
        return $pemesanan;
    }


    //ADMIN
    //Menampilkan Data 
    public function index(){
        //return"Menu Index";
        $menu = Menu::all();
        return view('Admin/indexmenu', ['menu' => $menu]);
    }
    //Menampilkan Form Input
    public function create(){
        return view('Admin/formtambahmenu');
    }
    //Menyimpan Data Ke Database
    public function store(Request $request){
        $this->validate($request,[
            'nama_menu' => "required",
            'harga_menu' => "required",
        ]);
        $status = Menu::create([
            'nama_menu' => $request->nama_menu,
            'harga_menu' => $request->harga_menu,
            'kategori' => $request->kategori,
            'status' => $request->status,
            'deskripsi' => $request->deskripsi,
            'waktu_penyajian' => $request->waktu_penyajian,
        ]);
        if($status){
            return redirect('/Admin/Menu')->with('success', 'Menu Berhasil Ditambahkan');
        }else{
            return redirect('/Admin/Menu')->with('error', 'Menu Gagal Ditambahkan');
        }
    }
    //Menampilkan Form Edit Beserta Data Yang Akan diEdit
    public function edit($id_menu){
        $menu = Menu::find($id_menu);
        return view('Admin/formtambahmenu', ['menu' => $menu]);

    }
    //Mengubah Data Yang di Update kedalam Database
    public function update(Request $request, $id_menu){
        $this->validate($request,[
            'nama_menu' => "required",
            'harga_menu' => "required",
        ]);
        $status = Menu::find($id_menu);
        $status->update([
            'nama_menu' => $request->nama_menu,
            'harga_menu' => $request->harga_menu,
            'kategori' => $request->kategori,
            'status' => $request->status,
            'deskripsi' => $request->deskripsi,
            'waktu_penyajian' => $request->waktu_penyajian,
        ]);
        if($status){
            return redirect('/Admin/Menu')->with('success', 'Menu Berhasil Diubah');
        }else{
            return redirect('/Admin/Menu')->with('error', 'Menu Gagal Diubah');
        }

    }
    //Menghapus Data Makanan
    public function destroy($id_menu){
        $status = Menu::find($id_menu);
        $status->delete();
        if($status){
            return redirect('/Admin/Menu')->with('success', 'Menu Berhasil Dihapus');
        }else{
            return redirect('/Admin/Menu')->with('error', 'Menu Gagal Dihapus');
        }
    }
    //Mengambil semua data menu kategori makanan
    public function getMakanan(){
        $listMa = Menu::select('*')
            ->where('kategori', '=', "Makanan")
            ->where('status', '=', "Tersedia")
            ->get();
        return $listMa;
    }
    //Mengambil semua data menu kategori minuman
    public function getMinuman(){
        $listMi = Menu::select('*')
            ->where('kategori', '=', "Minuman")
            ->where('status', '=', "Tersedia")
            ->get();
        return $listMi;
    }
    public function getMenuById($id){
        /*$listMenu = Menu::select('*')
            ->where('kategori', '=', "Minuman")
            ->get();*/
        $listMenu = Menu::find($id);
        return $listMenu;
    }
}
