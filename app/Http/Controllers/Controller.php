<?php

namespace App\Http\Controllers;

use App\Models\Filter_token;
use App\Models\Kecap;
use App\Models\Tek;
use GuzzleHttp\Psr7\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Contracts\DataTable;
use Yajra\DataTables\Facades\DataTables;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function indexHalaman()
    {

        $banyak_kecap = Kecap::count();
        $banyak_teks = Tek::count();
 
        return view('halaman.halaman1', compact(['banyak_kecap','banyak_teks']));
		
    }

    public function prosesKata()
    {
  
		$kecapInput=request('inputKata');
		
        $output = Controller::kataProses($kecapInput);
        array_shift($output);
		
        $separatedOutput ='' ;
        if (count($output)%9==0) {
            $separatedOutput = array_chunk($output,9);
        }
        else if(count($output)%10==0)
        {
            $separatedOutput = array_chunk($output,10);
        }
        else if(count($output)%11==0)
        {
            $separatedOutput = array_chunk($output,11);
        }
   
		
		foreach ($separatedOutput as $key => $value) {
			foreach ($value as $key2 => $value2) {
				# code...
				$newOutput [$key][explode(":",$value2)[0]]= explode(":",$value2)[1];
			}
		}
		// dd(collect($newOutput) );

		// dd($separatedOutput);
		if (isset($newOutput)) {
			# code...
		$collectNewOutput = collect($newOutput);
		$dataJumlah = [
			'jumlahPrefiks' => Controller::has_dupes($collectNewOutput->pluck('Prefiks ')),
			'jumlahInfiks' => Controller::has_dupes($collectNewOutput->pluck('Infiks ')),
			'jumlahSufiks' => Controller::has_dupes($collectNewOutput->pluck('Sufiks ')),
			'jumlahConfiks' => Controller::has_dupes($collectNewOutput->pluck('Confiks ')),	
		];	}
		else{
			$dataJumlah = [
				'jumlahPrefiks' => '',
				'jumlahInfiks' => '',
				'jumlahSufiks' => '',
				'jumlahConfiks' => '',	
			];
		}

		// dd(Controller::has_dupes($collectNewOutput->pluck('Confiks ')->toArray()));

	$banyak_kecap = Kecap::count();
    $banyak_teks = Tek::count();

	

    return view('halaman.halaman1',[
		'output'=> $separatedOutput , 
		'banyak_kecap'=>$banyak_kecap ,
		'banyak_teks'=>$banyak_teks,
		'data_jumlah'=>$dataJumlah,
	
	]);

    }


	public function prosesKataFromLink($kata)
    {

        $output = Controller::kataProses($kata);
        array_shift($output);
		
        $separatedOutput ='' ;
        if (count($output)%9==0) {
            $separatedOutput = array_chunk($output,9);
        }
        else if(count($output)%10==0)
        {
            $separatedOutput = array_chunk($output,10);
        }
        else if(count($output)%11==0)
        {
            $separatedOutput = array_chunk($output,11);
        }
   


	$banyak_kecap = Kecap::count();
    $banyak_teks = Tek::count();
	$kataToken =Filter_token::where('token',$kata)->first();


	$tek = isset($kataToken) ? Tek::where('id_teks',$kataToken->id_teks)->first()->teks : '';
	
		// dd(Filter_token::select('token')->get()->toJson());
		
	
    return 
	isset($kataToken) ?
	view('halaman.kata',
			['output'=> $separatedOutput , 
			'banyak_kecap'=>$banyak_kecap ,
			'banyak_teks'=>$banyak_teks ,
			'data_kata' => [
				'kata'=>$kata, 
				'nasal'=>$kataToken->nasal, 
				'kantetan'=>$kataToken->kantetan, 
				'dwilingga'=>$kataToken->dwilingga, 
				'dwiwasana'=>$kataToken->dwiwasana, 
				'prefiks'=>$kataToken->prefiks, 
				'trilingga'=>$kataToken->trilingga, 
				'infiks'=>$kataToken->infiks, 
				'sufiks'=>$kataToken->sufiks, 
				'simulfiks'=>$kataToken->simulfiks, 
				'akronim'=>$kataToken->akronim, ],
			'teks'=>$tek,
			
		
		]) :

		view('halaman.kata',
			['output'=> $separatedOutput , 
			'banyak_kecap'=>$banyak_kecap ,
			'banyak_teks'=>$banyak_teks ,
		])

		;

    }

	public function prosesTabelKata()
	{
		// $query = Kecap::query(); 
		$query = DB::table('filter_tokens')->select('id_token','token'); 

		// dd($query);
		// return DataTables::eloquent($query)
	
		return Datatables::queryBuilder($query)
		->addColumn('action', function ($user) {
			$urlHalaman = route('halaman1.kata',['kata'=>$user->token]);
			return 
			'<a href="'.$urlHalaman.'" class="btn btn-dark">Detail</a>'
			// '<a href="#link" class="btn btn-info" role="button">Link Button</a>'
			;
		})
		->make(true);
		// ->make(true);
		
	}

	public static function kataProses($katadarirequest)
	{
		         // kanonik rule
				 $rules = array('V','VK','KV','KVK','KKV','KKVK','VV','VVK','VKV','VKVK','VKKV','VKKVK','VKVK','VKKV','VKKKV','VKKKVK','KVV','KVVK','KVKV','KVKVK','KVKKV','KVKKVK','KVKKV','KVKKVK','KVKKKV','KVKKKVK','VVKV','VKVKV','VKVKVK','VKKVKV','VKKVVK','VKKVKVK','VKKVKKVK','VKKVKKV','KVKVVK','KVKVKV','KVKVV','KVKVKVK','KVKVKKVK','KVVKVK','KVKVKKKVK','KVKKVKVK','KVKKVKV','KVKVKKV','KVKKVKVK','KVKKVVK','KVKKVKKVK','KVKKVKKKVK','KKVKVKVK','VKVKVKV','VKVKVVK','VKVKKVVK','VKVKVKVK','KVKVKKKVKVKKVKVKVK','VKKVKVKV','KVKVKVKV','KVKVKVKVK','KVKVKVKV','KVKVKVKKVK','KVKVVKKVK','KVKVVKV','KVKKVKVKVK','KVKVKKVKV','KVKVVKVK','KVKKVKVKV','KVKKVKVV','KVKKVVKVK','KVKVKKVKVK','KVKVKKVVK','VKVKVKVKVK','KVKVKVKVKV','KVKVKVKVKKV','KVKVKVKVKKVK','KVKKVKVKVKVK','KVKVKVKVKKVK','KVKVKVKVVK','VKK','KVKK','KKKV','KKVKK','KKKVK','KVKKK');
				 $output[] = "";
				 // baca input
				 if($katadarirequest)
					 {
			 
						 $kecap = $katadarirequest;
						 $hasilPreprocessing = "";
						 $katasementara = '';
						 $katajadi = [];
			 
						 for ($arrayke = 0, $panjangnya = strlen($kecap); $arrayke < $panjangnya; $arrayke++){
							 if ($kecap[$arrayke] === ' '){
								 $katajadi[] = $katasementara;
								 $katasementara = '';
								 }
							 else{
								 $katasementara .= $kecap[$arrayke];
								 }
							 }
						 
						 $katajadi[] = $katasementara;
			 
						 for ($i=0; $i < $handleerror = count($katajadi); $i++){
						 
						 // Prefiks
						 $pref1 = "ba";								
						 $pref2 = "di";
						 $pref3 = "ka";
						 $pref4 = "ti";
						 $pref5 = "pa";
						 $pref6 = "pang";
						 $pref7 = "per";
						 $pref8 = "pi";
						 $pref9 = "sa";
						 $pref10 = "sang";
						 $pref11 = "si";
						 $pref12 = "ting";
			 
						 // Infiks
						 $inf1 = "ar";
						 $inf2 = "al";
						 $inf3 = "um";
						 $inf4 = "in";
			 
						 // Sufiks
						 $suf1 = "an";
						 $suf2 = "eun";
						 $suf3 = "keun";
						 $suf4 = "na";
						 $suf5 = "ing";
						 $suf6 = "ning";
			 
						 // Nasal (alomorf)
						 $alo1 = "m";
						 $alo2 = "n";
						 $alo3 = "ny";
						 $alo4 = "ng";
						 $alo5 = "nga";
						 $alo6 = "nge";
			 
						 $str = "";
			 
						 // Pecah teks
						 $split = str_split($katajadi[$i]);
						 $string = count($split);
			 
						 $konsonan = '/([bcdfghjklmnpqrstwxyz])/i';
						 $vokal = '/([aeiou])/i';
						 $k = preg_replace($konsonan, 'K', $katajadi[$i]);
						 $kv = preg_replace($vokal, 'V', $k);
			 
						 
						 
						 // Kata kurang dari atau sama dengan 5 huruf
						 if($string <= 4){
			 
							 $trim = preg_replace("/^$pref2/", '', $katajadi[$i]);
							 $fonem = preg_replace($konsonan, 'K', $trim);
							 $fonem_kv = preg_replace($vokal, 'V', $fonem);
			 
							 // n mengganti t
							 if(preg_match("/^n/", substr($katajadi[$i], 0))){
								 $trim_t = preg_replace("/^n/", 't', $katajadi[$i]);
								 $fonem_t = preg_replace($konsonan, 'K', $trim_t);
								 $fonem_tkv = preg_replace($vokal, 'V', $fonem_t);
								 // Cek pola kanonik
									 if(in_array($fonem_tkv, $rules)){
										 array_push($output , "Kata dasar: ".$katajadi[$i]." ");
										 array_push($output , "Pola fonem: ".$kv." ");
										 array_push($output , "Prefiks : n  ");
										 array_push($output , "Infiks : -  ");
										 array_push($output , "Sufiks : -  ");
										 array_push($output , "Confiks : -  ");
										 array_push($output , "Ambifiks :  -  ");
										 array_push($output , "Kata dasar : ".$trim_t." ");
										 array_push($output , "Pola kanonik : ".$fonem_tkv." ");
										  
										 }
									 else{
										 array_push($output , "Kata dasar: ".$katajadi[$i]." ");
										 array_push($output , "Pola fonem: ".$kv." ");
										 array_push($output , "Prefiks : t  ");
										 array_push($output , "Infiks : -  ");
										 array_push($output , "Sufiks : -  ");
										 array_push($output , "Confiks : -  ");
										 array_push($output , "Ambifiks :  -  ");
										 array_push($output , "Kata dasar : ".$trim_t." ");
										 array_push($output , "Pola kanonik : -  ");
										  
									 }
								 }
			 
							 // ny mengganti c, j, s
							 if(preg_match("/^ny/", substr($katajadi[$i], 0))){
								 $trim_t = preg_replace("/^ny/", 'c|j|s', $katajadi[$i]);
								 $fonem_t = preg_replace($konsonan, 'K', $trim_t);
								 $fonem_tkv = preg_replace($vokal, 'V', $fonem_t);
								 // Cek pola kanonik
									 if(in_array($fonem_tkv, $rules)){
										 array_push($output , "Kata dasar: ".$katajadi[$i]." ");
										 array_push($output , "Pola fonem: ".$kv." ");
										 array_push($output , "Prefiks : c|j|s ");
										 array_push($output , "Infiks : -  ");
										 array_push($output , "Sufiks : -  ");
										 array_push($output , "Confiks : -  ");
										 array_push($output , "Ambifiks :  -  ");
										 array_push($output , "Kata dasar : ".$trim_t." ");
										 array_push($output , "Pola kanonik : ".$fonem_tkv." ");
										  
										 }
									 else{
										 array_push($output , "Kata dasar: ".$katajadi[$i]." ");
										 array_push($output , "Pola fonem: ".$kv." ");
										 array_push($output , "Prefiks : c|j|s ");
										 array_push($output , "Infiks : -  ");
										 array_push($output , "Sufiks : -  ");
										 array_push($output , "Confiks : -  ");
										 array_push($output , "Ambifiks :  -  ");
										 array_push($output , "Kata dasar : ".$trim_t." ");
										 array_push($output , "Pola kanonik : -  ");
										  
									 }
								 }
			 
							 // m mengganti p,b
							 if(preg_match("/^m/", substr($katajadi[$i], 0))){
								 $trim_t = preg_replace("/^m/", 'p|b', $katajadi[$i]);
								 $fonem_t = preg_replace($konsonan, 'K', $trim_t);
								 $fonem_tkv = preg_replace($vokal, 'V', $fonem_t);
								 // Cek pola kanonik
									 if(in_array($fonem_tkv, $rules)){
										 array_push($output , "Kata dasar: ".$katajadi[$i]." ");
										 array_push($output , "Pola fonem: ".$kv." ");
										 array_push($output , "Prefiks : p|b ");
										 array_push($output , "Infiks : -  ");
										 array_push($output , "Sufiks : -  ");
										 array_push($output , "Confiks : -  ");
										 array_push($output , "Ambifiks :  -  ");
										 array_push($output , "Kata dasar : ".$trim_t." ");
										 array_push($output , "Pola kanonik : ".$fonem_tkv." ");
										  
										 }
									 else{
										 array_push($output , "Kata dasar: ".$katajadi[$i]." ");
										 array_push($output , "Pola fonem: ".$kv." ");
										 array_push($output , "Prefiks : t ");
										 array_push($output , "Infiks : -  ");
										 array_push($output , "Sufiks : -  ");
										 array_push($output , "Confiks : -  ");
										 array_push($output , "Ambifiks :  -  ");
										 array_push($output , "Kata dasar : ".$trim_t." ");
										 array_push($output , "Pola kanonik : -  ");
										  
									 }
								 }
			 
							 // ng mengganti k
							 if(preg_match("/^ng/", substr($katajadi[$i], 0))){
								 $trim_t = preg_replace("/^ng/", 'k', $katajadi[$i]);
								 $fonem_t = preg_replace($konsonan, 'K', $trim_t);
								 $fonem_tkv = preg_replace($vokal, 'V', $fonem_t);
								 // Cek pola kanonik
									 if(in_array($fonem_tkv, $rules)){
										 array_push($output , "Kata dasar: ".$katajadi[$i]." ");
										 array_push($output , "Pola fonem: ".$kv." ");
										 array_push($output , "Prefiks : ng ");
										 array_push($output , "Infiks : -  ");
										 array_push($output , "Sufiks : -  ");
										 array_push($output , "Confiks : -  ");
										 array_push($output , "Ambifiks :  -  ");
										 array_push($output , "Kata dasar : ".$trim_t." ");
										 array_push($output , "Pola kanonik : ".$fonem_tkv." ");
										  
										 }
									 else{
										 array_push($output , "Kata dasar: ".$katajadi[$i]." ");
										 array_push($output , "Pola fonem: ".$kv." ");
										 array_push($output , "Prefiks : ng ");
										 array_push($output , "Infiks : -  ");
										 array_push($output , "Sufiks : -  ");
										 array_push($output , "Confiks : -  ");
										 array_push($output , "Ambifiks :  -  ");
										 array_push($output , "Kata dasar : ".$trim_t." ");
										 array_push($output , "Pola kanonik : -  ");
										  
									 }
								 }
			 
							 // ng didepan huruf vokal
							 if(preg_match("/^ng[aiueo]/", substr($katajadi[$i], 0))){
								 $trim_t = preg_replace("/^ng/", '', $katajadi[$i]);
								 $fonem_t = preg_replace($konsonan, 'K', $trim_t);
								 $fonem_tkv = preg_replace($vokal, 'V', $fonem_t);
								 // Cek pola kanonik
									 if(in_array($fonem_tkv, $rules)){
										 array_push($output , "Kata dasar: ".$katajadi[$i]." ");
										 array_push($output , "Pola fonem: ".$kv." ");
										 array_push($output , "Prefiks : ng ");
										 array_push($output , "Infiks : -  ");
										 array_push($output , "Sufiks : -  ");
										 array_push($output , "Confiks : -  ");
										 array_push($output , "Ambifiks :  -  ");
										 array_push($output , "Kata dasar : ".$trim_t." ");
										 array_push($output , "Pola kanonik : ".$fonem_tkv." ");
										  
										 }
									 else{
										 array_push($output , "Kata dasar: ".$katajadi[$i]." ");
										 array_push($output , "Pola fonem: ".$kv." ");
										 array_push($output , "Prefiks : ng ");
										 array_push($output , "Infiks : -  ");
										 array_push($output , "Sufiks : -  ");
										 array_push($output , "Confiks : -  ");
										 array_push($output , "Ambifiks :  -  ");
										 array_push($output , "Kata dasar : ".$trim_t." ");
										 array_push($output , "Pola kanonik : -  ");
										  
									 }
								 }
			 
							 // ng berubah nga didepan huruf d
							 if(preg_match("/^ng/", substr($katajadi[$i], 0))){
								 $trim_t = preg_replace("/^ng/", 'd', $katajadi[$i]);
								 $fonem_t = preg_replace($konsonan, 'K', $trim_t);
								 $fonem_tkv = preg_replace($vokal, 'V', $fonem_t);
								 // Cek pola kanonik
									 if(in_array($fonem_tkv, $rules)){
										 array_push($output , "Kata dasar: ".$katajadi[$i]." ");
										 array_push($output , "Pola fonem: ".$kv." ");
										 array_push($output , "Prefiks : ng ");
										 array_push($output , "Infiks : -  ");
										 array_push($output , "Sufiks : -  ");
										 array_push($output , "Confiks : -  ");
										 array_push($output , "Ambifiks :  -  ");
										 array_push($output , "Kata dasar : ".$trim_t." ");
										 array_push($output , "Pola kanonik : ".$fonem_tkv." ");
										  
										 }
									 else{
										 array_push($output , "Kata dasar: ".$katajadi[$i]." ");
										 array_push($output , "Pola fonem: ".$kv." ");
										 array_push($output , "Prefiks : t ");
										 array_push($output , "Infiks : -  ");
										 array_push($output , "Sufiks : -  ");
										 array_push($output , "Confiks : -  ");
										 array_push($output , "Ambifiks :  -  ");
										 array_push($output , "Kata dasar : ".$trim_t." ");
										 array_push($output , "Pola kanonik : -  ");
										  
									 }
								 }
			 
							 // ng berubah nge didepan huruf c
							 if(preg_match("/^nge/", substr($katajadi[$i], 0))){
								 $trim_t = preg_replace("/^nge/", 'c', $katajadi[$i]);
								 $fonem_t = preg_replace($konsonan, 'K', $trim_t);
								 $fonem_tkv = preg_replace($vokal, 'V', $fonem_t);
								 // Cek pola kanonik
									 if(in_array($fonem_tkv, $rules)){
										 array_push($output , "Kata dasar: ".$katajadi[$i]." ");
										 array_push($output , "Pola fonem: ".$kv." ");
										 array_push($output , "Prefiks : nge ");
										 array_push($output , "Infiks : -  ");
										 array_push($output , "Sufiks : -  ");
										 array_push($output , "Confiks : -  ");
										 array_push($output , "Ambifiks :  -  ");
										 array_push($output , "Kata dasar : ".$trim_t." ");
										 array_push($output , "Pola kanonik : ".$fonem_tkv." ");
										  
										 }
									 else{
										 array_push($output , "Kata dasar: ".$katajadi[$i]." ");
										 array_push($output , "Pola fonem: ".$kv." ");
										 array_push($output , "Prefiks : t ");
										 array_push($output , "Infiks : -  ");
										 array_push($output , "Sufiks : -  ");
										 array_push($output , "Confiks : -  ");
										 array_push($output , "Ambifiks :  -  ");
										 array_push($output , "Kata dasar : ".$trim_t." ");
										 array_push($output , "Pola kanonik : -  ");
										  
									 }
								 }
			 
							 
							 if(in_array($kv, $rules)){
								 array_push($output , "Kata dasar: ".$katajadi[$i]." ");
								 array_push($output , "Pola fonem: ".$kv." ");
								 array_push($output , "Prefiks : -  ");
								 array_push($output , "Infiks : -  ");
								 array_push($output , "Sufiks : -  ");
								 array_push($output , "Confiks : -  ");
								 array_push($output , "Ambifiks :  -  ");
								 array_push($output , "Kata dasar : ".$trim." ");
								 array_push($output , "Pola kanonik : ".$fonem_kv." ");
								  
								 }
							 else{
								 array_push($output , "Kata dasar: ".$katajadi[$i]." ");
								 array_push($output , "Pola fonem: ".$kv." ");
								 array_push($output , "Prefiks : -  ");
								 array_push($output , "Infiks : -  ");
								 array_push($output , "Sufiks : -  ");
								 array_push($output , "Confiks : -  ");
								 array_push($output , "Ambifiks :  -  ");
								 array_push($output , "Kata dasar : ".$trim." ");
								 array_push($output , "Pola kanonik : -  ");
								  
								 }					
							 }
			 
						 // Kata antara 5 sampai 12 huruf 
						 elseif($string >= 5 && $string <= 12){
							 
							 // Prefiks
							 // N
			 
							 // ny mengganti c, j, s
							 if(preg_match("/^ny/", substr($katajadi[$i], 0))){
								 $trim_t = preg_replace("/^ny/", 'c|j|s', $katajadi[$i]);
								 $fonem_t = preg_replace($konsonan, 'K', $trim_t);
								 $fonem_tkv = preg_replace($vokal, 'V', $fonem_t);
								 // Cek pola kanonik
									 if(in_array($fonem_tkv, $rules)){
										 array_push($output , "Kata dasar: ".$katajadi[$i]." ");
										 array_push($output , "Pola fonem: ".$kv." ");
										 array_push($output , "Prefiks : c|j|s ");
										 array_push($output , "Infiks : -  ");
										 array_push($output , "Sufiks : -  ");
										 array_push($output , "Confiks : -  ");
										 array_push($output , "Ambifiks :  -  ");
										 array_push($output , "Kata dasar : ".$trim_t." ");
										 array_push($output , "Pola kanonik : ".$fonem_tkv." ");
										  
										 }
									 else{
										 array_push($output , "Kata dasar: ".$katajadi[$i]." ");
										 array_push($output , "Pola fonem: ".$kv." ");
										 array_push($output , "Prefiks : c|j|s ");
										 array_push($output , "Infiks : -  ");
										 array_push($output , "Sufiks : -  ");
										 array_push($output , "Confiks : -  ");
										 array_push($output , "Ambifiks :  -  ");
										 array_push($output , "Kata dasar : ".$trim_t." ");
										 array_push($output , "Pola kanonik : -  ");
										  
									 }
								 }
			 
							 // m mengganti p,b
							 if(preg_match("/^m/", substr($katajadi[$i], 0))){
								 $trim_t = preg_replace("/^m/", 'p|b', $katajadi[$i]);
								 $fonem_t = preg_replace($konsonan, 'K', $trim_t);
								 $fonem_tkv = preg_replace($vokal, 'V', $fonem_t);
								 // Cek pola kanonik
									 if(in_array($fonem_tkv, $rules)){
										 array_push($output , "Kata dasar: ".$katajadi[$i]." ");
										 array_push($output , "Pola fonem: ".$kv." ");
										 array_push($output , "Prefiks : p|b ");
										 array_push($output , "Infiks : -  ");
										 array_push($output , "Sufiks : -  ");
										 array_push($output , "Confiks : -  ");
										 array_push($output , "Ambifiks :  -  ");
										 array_push($output , "Kata dasar : ".$trim_t." ");
										 array_push($output , "Pola kanonik : ".$fonem_tkv." ");
										  
										 }
									 else{
										 array_push($output , "Kata dasar: ".$katajadi[$i]." ");
										 array_push($output , "Pola fonem: ".$kv." ");
										 array_push($output , "Prefiks : t ");
										 array_push($output , "Infiks : -  ");
										 array_push($output , "Sufiks : -  ");
										 array_push($output , "Confiks : -  ");
										 array_push($output , "Ambifiks :  -  ");
										 array_push($output , "Kata dasar : ".$trim_t." ");
										 array_push($output , "Pola kanonik : -  ");
										  
									 }
								 }
			 
							 // ng mengganti k
							 if(preg_match("/^ng/", substr($katajadi[$i], 0))){
								 $trim_t = preg_replace("/^ng/", 'k', $katajadi[$i]);
								 $fonem_t = preg_replace($konsonan, 'K', $trim_t);
								 $fonem_tkv = preg_replace($vokal, 'V', $fonem_t);
								 // Cek pola kanonik
									 if(in_array($fonem_tkv, $rules)){
										 array_push($output , "Kata dasar: ".$katajadi[$i]." ");
										 array_push($output , "Pola fonem: ".$kv." ");
										 array_push($output , "Prefiks : ng ");
										 array_push($output , "Infiks : -  ");
										 array_push($output , "Sufiks : -  ");
										 array_push($output , "Confiks : -  ");
										 array_push($output , "Ambifiks :  -  ");
										 array_push($output , "Kata dasar : ".$trim_t." ");
										 array_push($output , "Pola kanonik : ".$fonem_tkv." ");
										  
										 }
									 else{
										 array_push($output , "Kata dasar: ".$katajadi[$i]." ");
										 array_push($output , "Pola fonem: ".$kv." ");
										 array_push($output , "Prefiks : ng ");
										 array_push($output , "Infiks : -  ");
										 array_push($output , "Sufiks : -  ");
										 array_push($output , "Confiks : -  ");
										 array_push($output , "Ambifiks :  -  ");
										 array_push($output , "Kata dasar : ".$trim_t." ");
										 array_push($output , "Pola kanonik : -  ");
										  
									 }
								 }
			 
							 // ng didepan huruf vokal
							 if(preg_match("/^nga|^ngi|^ngu|^nge|^ngo/", substr($katajadi[$i], 0))){
								 $trim_t = preg_replace("/^nga|^ngi|^ngu|^nge|^ngo/", '', $katajadi[$i]);
								 $fonem_t = preg_replace($konsonan, 'K', $trim_t);
								 $fonem_tkv = preg_replace($vokal, 'V', $fonem_t);
								 // Cek pola kanonik
									 if(in_array($fonem_tkv, $rules)){
										 array_push($output , "Kata dasar: ".$katajadi[$i]." ");
										 array_push($output , "Pola fonem: ".$kv." ");
										 array_push($output , "Prefiks : ng ");
										 array_push($output , "Infiks : -  ");
										 array_push($output , "Sufiks : -  ");
										 array_push($output , "Confiks : -  ");
										 array_push($output , "Ambifiks :  -  ");
										 array_push($output , "Kata dasar : ".$trim_t." ");
										 array_push($output , "Pola kanonik : ".$fonem_tkv." ");
										  
										 }
									 else{
										 array_push($output , "Kata dasar: ".$katajadi[$i]." ");
										 array_push($output , "Pola fonem: ".$kv." ");
										 array_push($output , "Prefiks : ng ");
										 array_push($output , "Infiks : -  ");
										 array_push($output , "Sufiks : -  ");
										 array_push($output , "Confiks : -  ");
										 array_push($output , "Ambifiks :  -  ");
										 array_push($output , "Kata dasar : ".$trim_t." ");
										 array_push($output , "Pola kanonik : -  ");
										  
									 }
								 }
			 
							 // ng berubah nga didepan huruf d
							 if(preg_match("/^ng/", substr($katajadi[$i], 0))){
								 $trim_t = preg_replace("/^ng/", 'd', $katajadi[$i]);
								 $fonem_t = preg_replace($konsonan, 'K', $trim_t);
								 $fonem_tkv = preg_replace($vokal, 'V', $fonem_t);
								 // Cek pola kanonik
									 if(in_array($fonem_tkv, $rules)){
										 array_push($output , "Kata dasar: ".$katajadi[$i]." ");
										 array_push($output , "Pola fonem: ".$kv." ");
										 array_push($output , "Prefiks : ng ");
										 array_push($output , "Infiks : -  ");
										 array_push($output , "Sufiks : -  ");
										 array_push($output , "Confiks : -  ");
										 array_push($output , "Ambifiks :  -  ");
										 array_push($output , "Kata dasar : ".$trim_t." ");
										 array_push($output , "Pola kanonik : ".$fonem_tkv." ");
										  
										 }
									 else{
										 array_push($output , "Kata dasar: ".$katajadi[$i]." ");
										 array_push($output , "Pola fonem: ".$kv." ");
										 array_push($output , "Prefiks : t ");
										 array_push($output , "Infiks : -  ");
										 array_push($output , "Sufiks : -  ");
										 array_push($output , "Confiks : -  ");
										 array_push($output , "Ambifiks :  -  ");
										 array_push($output , "Kata dasar : ".$trim_t." ");
										 array_push($output , "Pola kanonik : -  ");
										  
									 }
								 }
			 
							 // ng berubah nge didepan huruf c
							 if(preg_match("/^nge/", substr($katajadi[$i], 0))){
								 $trim_t = preg_replace("/^nge/", 'c', $katajadi[$i]);
								 $fonem_t = preg_replace($konsonan, 'K', $trim_t);
								 $fonem_tkv = preg_replace($vokal, 'V', $fonem_t);
								 // Cek pola kanonik
									 if(in_array($fonem_tkv, $rules)){
										 array_push($output , "Kata dasar: ".$katajadi[$i]." ");
										 array_push($output , "Pola fonem: ".$kv." ");
										 array_push($output , "Prefiks : nge ");
										 array_push($output , "Infiks : -  ");
										 array_push($output , "Sufiks : -  ");
										 array_push($output , "Confiks : -  ");
										 array_push($output , "Ambifiks :  -  ");
										 array_push($output , "Kata dasar : ".$trim_t." ");
										 array_push($output , "Pola kanonik : ".$fonem_tkv." ");
										  
										 }
									 else{
										 array_push($output , "Kata dasar: ".$katajadi[$i]." ");
										 array_push($output , "Pola fonem: ".$kv." ");
										 array_push($output , "Prefiks : t ");
										 array_push($output , "Infiks : -  ");
										 array_push($output , "Sufiks : -  ");
										 array_push($output , "Confiks : -  ");
										 array_push($output , "Ambifiks :  -  ");
										 array_push($output , "Kata dasar : ".$trim_t." ");
										 array_push($output , "Pola kanonik : -  ");
										  
									 }
								 }
			 
							 // ba-
							 if(preg_match("/^$pref1/", substr($katajadi[$i], 0))){
								 $trim_pref1 = preg_replace("/^$pref1/", '', $katajadi[$i]);
								 $fonem_pref1 = preg_replace($konsonan, 'K', $trim_pref1);
								 $fonem_pref1kv = preg_replace($vokal, 'V', $fonem_pref1);
			 
								 // Cek pola kanonik
								 if(in_array($fonem_pref1kv, $rules)){
									 array_push($output , "Kata dasar: ".$katajadi[$i]." ");
									 array_push($output , "Pola fonem: ".$kv." ");
									 array_push($output , "Prefiks : ".$pref1." ");
									 array_push($output , "Infiks : -  ");
									 array_push($output , "Sufiks : -  ");
									 array_push($output , "Confiks : -  ");
									 array_push($output , "Ambifiks :  -  ");
									 array_push($output , "Kata dasar : ".$trim_pref1." ");
									 array_push($output , "Pola kanonik : ".$fonem_pref1kv." ");
									  
									 }
								 else{
									 array_push($output , "Kata dasar: ".$katajadi[$i]." ");
									 array_push($output , "Pola fonem: ".$kv." ");
									 array_push($output , "Prefiks : ".$pref1." ");
									 array_push($output , "Infiks : -  ");
									 array_push($output , "Sufiks : -  ");
									 array_push($output , "Confiks : -  ");
									 array_push($output , "Ambifiks :  -  ");
									 array_push($output , "Kata dasar : ".$trim_pref1." ");
									 array_push($output , "Pola kanonik : -  ");
									  
									 }
								 }
			 
							 // per-
							 if(preg_match("/^$pref7/", substr($katajadi[$i], 0))){
								 $trim_pref7 = preg_replace("/$pref7/", '', $katajadi[$i]);
								 $fonem_pref7 = preg_replace($konsonan, 'K', $trim_pref7);
								 $fonem_pref7kv = preg_replace($vokal, 'V', $fonem_pref7);
								 
								 // -an
								 if(preg_match("/$suf1$/", substr($trim_pref7, 0))){
									 $trim_suf1 = preg_replace("/$suf1$/", '', $trim_pref7);
									 $fonem_suf1 = preg_replace($konsonan, 'K', $trim_suf1);
									 $fonem_suf1kv = preg_replace($vokal, 'V', $fonem_suf1);
			 
									 // Cek pola kanonik
									 if(in_array($fonem_suf1kv, $rules)){
										 array_push($output , "Kata dasar: ".$trim_pref7." ");
										 array_push($output , "Pola fonem: ".$fonem_pref7kv." ");
										 array_push($output , "Prefiks : ".$pref7." ");
										 array_push($output , "Infiks : -  ");
										 array_push($output , "Sufiks : ".$suf1." ");
										 array_push($output , "Confiks : ".$pref7."+ - +".$suf1." ");
										 array_push($output , "Ambifiks :  -  ");
										 array_push($output , "Kata dasar : ".$trim_suf1." ");
										 array_push($output , "Pola kanonik : ".$fonem_suf1kv." ");
										  
										 }
									 else{
										 array_push($output , "Kata dasar: ".$trim_pref7." ");
										 array_push($output , "Pola fonem: ".$fonem_pref7kv." ");
										 array_push($output , "Prefiks : ".$pref7." ");
										 array_push($output , "Infiks : -  ");
										 array_push($output , "Sufiks : ".$suf1." ");
										 array_push($output , "Confiks : ".$pref7."+ - +".$suf1." ");
										 array_push($output , "Ambifiks :  -  ");
										 array_push($output , "Kata dasar : ".$trim_suf1." ");
										 array_push($output , "Pola kanonik : -  ");
										  
										 }
									 }
			 
								 else{
									 // Cek pola kanonik
									 if(in_array($fonem_pref7kv, $rules)){
										 array_push($output , "Kata dasar: ".$katajadi[$i]." ");
										 array_push($output , "Pola fonem: ".$kv." ");
										 array_push($output , "Prefiks : ".$pref7." ");
										 array_push($output , "Infiks : -  ");
										 array_push($output , "Sufiks : -  ");
										 array_push($output , "Confiks : -  ");
										 array_push($output , "Ambifiks :  -  ");
										 array_push($output , "Kata dasar : ".$trim_pref7." ");
										 array_push($output , "Pola kanonik : ".$fonem_pref7kv." ");
										  
										 }
									 else{
										 array_push($output , "Kata dasar: ".$katajadi[$i]." ");
										 array_push($output , "Pola fonem: ".$kv." ");
										 array_push($output , "Prefiks : ".$pref7." ");
										 array_push($output , "Infiks : -  ");
										 array_push($output , "Sufiks : -  ");
										 array_push($output , "Confiks : -  ");
										 array_push($output , "Ambifiks :  -  ");
										 array_push($output , "Kata dasar : ".$trim_pref7." ");
										 array_push($output , "Pola kanonik : -  ");
										  
										 }
									 }
								 }
			 
							 // si-
							 if(preg_match("/^$pref11/", substr($katajadi[$i], 0))){
								 $trim_pref11 = preg_replace("/^$pref11/", '', $katajadi[$i]);
								 $fonem_pref11 = preg_replace($konsonan, 'K', $trim_pref11);
								 $fonem_pref11kv = preg_replace($vokal, 'V', $fonem_pref11);
								 
								 // Cek pola kanonik
								 if(in_array($fonem_pref11kv, $rules)){
									 array_push($output , "Kata dasar: ".$katajadi[$i]." ");
									 array_push($output , "Pola fonem: ".$kv." ");
									 array_push($output , "Prefiks : -  ");
									 array_push($output , "Infiks : -  ");
									 array_push($output , "Sufiks : -  ");
									 array_push($output , "Confiks : -  ");
									 array_push($output , "Ambifiks :  -  ");
									 array_push($output , "Kata dasar : ".$trim_pref11." ");
									 array_push($output , "Pola kanonik : ".$fonem_pref11kv." ");
									  
									 }
								 else{
									 array_push($output , "Kata dasar: ".$katajadi[$i]." ");
									 array_push($output , "Pola fonem: ".$kv." ");
									 array_push($output , "Prefiks : ".$pref11." ");
									 array_push($output , "Infiks : -  ");
									 array_push($output , "Sufiks : -  ");
									 array_push($output , "Confiks : -  ");
									 array_push($output , "Ambifiks :  -  ");
									 array_push($output , "Kata dasar : ".$trim_pref11." ");
									 array_push($output , "Pola kanonik : -  ");
									  
									 }
								 }
			 
							 // di-ar-an|-keun
							 // di-pi(ka)-
							 // di-pang-ar-an|-keun
							 // di-
							 if(preg_match("/^$pref2/", substr($katajadi[$i], 0))){
								 $trim_pref2 = preg_replace("/^$pref2/", '', $katajadi[$i]);
								 $fonem_pref2 = preg_replace($konsonan, 'K', $trim_pref2);
								 $fonem_pref2kv = preg_replace($vokal, 'V', $fonem_pref2);
			 
								 // pi-
								 if(preg_match("/^$pref8/", substr($trim_pref2, 0))){
									 $trim_pref8 = preg_replace("/^$pref8/", '', $trim_pref2);
									 $fonem_pref8 = preg_replace($konsonan, 'K', $trim_pref8);
									 $fonem_pref8kv = preg_replace($vokal, 'V', $fonem_pref8);
			 
									 // ka-
									 if(preg_match("/^$pref3/", substr($trim_pref8, 0))){
										 $trim_pref3 = preg_replace("/^$pref3/", '', $trim_pref8);
										 $fonem_pref3 = preg_replace($konsonan, 'K', $trim_pref3);
										 $fonem_pref3kv = preg_replace($vokal, 'V', $fonem_pref3);
			 
										 // Cek pola kanonik
										 if(in_array($fonem_pref8kv, $rules)){
											 array_push($output , "Kata dasar: ".$katajadi[$i]." ");
											 array_push($output , "Pola fonem: ".$kv." ");
											 array_push($output , "Prefiks1 : ".$pref2." ");
											 array_push($output , "Prefiks2 : ".$pref8." ");
											 array_push($output , "Prefiks3 : ".$pref3." ");
											 array_push($output , "Infiks : -  ");
											 array_push($output , "Sufiks : -  ");
											 array_push($output , "Confiks : -  ");
											 array_push($output , "Ambifiks :  -  ");
											 array_push($output , "Kata dasar : ".$trim_pref3." ");
											 array_push($output , "Pola kanonik : ".$fonem_pref3kv." ");
											  
											 }
										 else{
											 array_push($output , "Kata dasar: ".$katajadi[$i]." ");
											 array_push($output , "Pola fonem: ".$kv." ");
											 array_push($output , "Prefiks1 : ".$pref2." ");
											 array_push($output , "Prefiks2 : ".$pref8." ");
											 array_push($output , "Infiks : -  ");
											 array_push($output , "Sufiks : -  ");
											 array_push($output , "Confiks : -  ");
											 array_push($output , "Ambifiks :  -  ");
											 array_push($output , "Kata dasar : ".$trim_pref8." ");							
											 array_push($output , "Pola kanonik : -  ");
											  
											 }
										 }
									 
									 else{
										 // Cek pola kanonik
										 if(in_array($fonem_pref8kv, $rules)){
											 array_push($output , "Kata dasar: ".$katajadi[$i]." ");
											 array_push($output , "Pola fonem: ".$kv." ");
											 array_push($output , "Prefiks1 : ".$pref2." ");
											 array_push($output , "Prefiks2 : ".$pref8." ");
											 array_push($output , "Infiks : -  ");
											 array_push($output , "Sufiks : -  ");
											 array_push($output , "Confiks : -  ");
											 array_push($output , "Ambifiks :  -  ");
											 array_push($output , "Kata dasar : ".$trim_pref8." ");
											 array_push($output , "Pola kanonik : ".$fonem_pref8kv." ");
											  
											 }
										 else{
											 array_push($output , "Kata dasar: ".$katajadi[$i]." ");
											 array_push($output , "Pola fonem: ".$kv." ");
											 array_push($output , "Prefiks1 : ".$pref2." ");
											 array_push($output , "Prefiks2 : ".$pref8." ");
											 array_push($output , "Infiks : -  ");
											 array_push($output , "Sufiks : -  ");
											 array_push($output , "Confiks : -  ");
											 array_push($output , "Ambifiks :  -  ");
											 array_push($output , "Kata dasar : ".$trim_pref8." ");							
											 array_push($output , "Pola kanonik : -  ");
											  
											 }
										 }
									 }
			 
								 // -an
								 elseif(preg_match("/$suf1$/", substr($trim_pref2, -2))){
									 $trim_suf1 = preg_replace("/$suf1$/", '', $trim_pref2);
									 $fonem_suf1 = preg_replace($konsonan, 'K', $trim_suf1);
									 $fonem_suf1kv = preg_replace($vokal, 'V', $fonem_suf1);
			 
									 // Cek pola kanonik
									 if(in_array($fonem_suf1kv, $rules)){
										 array_push($output , "Kata dasar: ".$katajadi[$i]." ");
										 array_push($output , "Pola fonem: ".$kv." ");
										 array_push($output , "Prefiks : ".$pref2." ");
										 array_push($output , "Infiks : -  ");
										 array_push($output , "Sufiks : ".$suf1." ");
										 array_push($output , "Confiks : ".$pref2."+ - +".$suf1." ");
										 array_push($output , "Ambifiks :  -  ");
										 array_push($output , "Kata dasar : ".$trim_suf1." ");
										 array_push($output , "Pola kanonik : ".$fonem_suf1kv." ");
										  
										 }
									 else{
										 array_push($output , "Kata dasar: ".$katajadi[$i]." ");
										 array_push($output , "Pola fonem: ".$kv." ");
										 array_push($output , "Prefiks : ".$pref2." ");
										 array_push($output , "Infiks : -  ");
										 array_push($output , "Sufiks : ".$suf1." ");
										 array_push($output , "Confiks : ".$pref2."+ - +".$suf1." ");
										 array_push($output , "Ambifiks :  -  ");
										 array_push($output , "Kata dasar : ".$trim_suf1." ");							
										 array_push($output , "Pola kanonik : -  ");
										  
										 }
			 
									 }
								 
								 // keun
								 elseif(preg_match("/$suf3$/", substr($trim_pref2, -4))){						
									 $trim_suf3 = preg_replace("/$suf3$/", '', $trim_pref2);
									 $fonem_suf3 = preg_replace($konsonan, 'K', $trim_suf3);
									 $fonem_suf3kv = preg_replace($vokal, 'V', $fonem_suf3);
			 
									 // Cek pola kanonik
									 if(in_array($fonem_suf3kv, $rules)){
										 array_push($output , "Kata dasar: ".$katajadi[$i]." ");
										 array_push($output , "Pola fonem: ".$kv." ");
										 array_push($output , "Prefiks1 : ".$pref2." ");
										 array_push($output , "Infiks : -  ");
										 array_push($output , "Sufiks : ".$suf3." ");
										 array_push($output , "Confiks : ".$pref2."+ - +".$suf3." ");
										 array_push($output , "Ambifiks :  -  ");
										 array_push($output , "Kata dasar : ".$trim_suf3." ");
										 array_push($output , "Pola kanonik : ".$fonem_suf3kv." ");
										  
										 }
									 else{
										 array_push($output , "Kata dasar: ".$katajadi[$i]." ");
										 array_push($output , "Pola fonem: ".$kv." ");
										 array_push($output , "Prefiks : ".$pref2." ");
										 array_push($output , "Infiks : -  ");
										 array_push($output , "Sufiks : ".$suf3." ");
										 array_push($output , "Confiks : ".$pref2."+ - +".$suf3." ");
										 array_push($output , "Ambifiks :  -  ");
										 array_push($output , "Kata dasar : ".$trim_suf3." ");							
										 array_push($output , "Pola kanonik : -  ");
										  
										 }
								 }
			 
								 elseif(preg_match("/^$pref8/", substr($trim_pref2, 0))){
			 
								 }
			 
								 elseif(preg_match("/^$pref8/", substr($trim_pref2, 0))){
			 
								 }
			 
								 else{
									 // Cek pola kanonik
									 if(in_array($fonem_pref2kv, $rules)){
										 array_push($output , "Kata dasar: ".$katajadi[$i]." ");
										 array_push($output , "Pola fonem: ".$kv." ");
										 array_push($output , "Prefiks : ".$pref2." ");
										 array_push($output , "Infiks : -  ");
										 array_push($output , "Sufiks : -  ");
										 array_push($output , "Confiks : -  ");
										 array_push($output , "Ambifiks :  -  ");
										 array_push($output , "Kata dasar : ".$trim_pref2." ");
										 array_push($output , "Pola kanonik : ".$fonem_pref2kv." ");
										  
										 }
									 else{
										 array_push($output , "Kata dasar: ".$katajadi[$i]." ");
										 array_push($output , "Pola fonem: ".$kv." ");
										 array_push($output , "Prefiks : ".$pref2." ");
										 array_push($output , "Infiks : -  ");
										 array_push($output , "Sufiks : -  ");
										 array_push($output , "Confiks : -  ");
										 array_push($output , "Ambifiks :  -  ");
										 array_push($output , "Kata dasar : ".$trim_pref2." ");
										 array_push($output , "Pola kanonik : -  ");
										  
										 }
									 }
								 }
			 
			 
							 // ka(pi)-an|-keun
							 if(preg_match("/^$pref3/", substr($katajadi[$i], 0))){
								 $trim_pref3 = preg_replace("/^$pref3/", '', $katajadi[$i]);
								 $fonem_pref3 = preg_replace($konsonan, 'K', $trim_pref3);
								 $fonem_pref3kv = preg_replace($vokal, 'V', $fonem_pref3);
			 
								 // pi-
								 if(preg_match("/^$pref8/", substr($trim_pref3, 0))){
									 $trim_pref81 = preg_replace("/^$pref8/", '', $trim_pref3);
									 $fonem_pref81 = preg_replace($konsonan, 'K', $trim_pref81);
									 $fonem_pref81kv = preg_replace($vokal, 'V', $fonem_pref81);
			 
									 // Cek pola kanonik
									 if(in_array($fonem_pref81kv, $rules)){
										 array_push($output , "Kata dasar: ".$katajadi[$i]." ");
										 array_push($output , "Pola fonem: ".$kv." ");
										 array_push($output , "Prefiks1 : ".$pref3." ");
										 array_push($output , "Prefiks2 : ".$pref8." ");
										 array_push($output , "Infiks : -  ");
										 array_push($output , "Sufiks : -  ");
										 array_push($output , "Confiks : ".$pref3.$pref8." ");
										 array_push($output , "Ambifiks :  -  ");
										 array_push($output , "Kata dasar : ".$trim_pref81." ");
										 array_push($output , "Pola kanonik : ".$fonem_pref81kv." ");
										  
										 }
									 else{
										 array_push($output , "Kata dasar: ".$katajadi[$i]." ");
										 array_push($output , "Pola fonem: ".$kv." ");
										 array_push($output , "Prefiks : ".$pref3." ");
										 array_push($output , "Infiks : -  ");
										 array_push($output , "Sufiks : -  ");
										 array_push($output , "Confiks : -  ");
										 array_push($output , "Ambifiks :  -  ");
										 array_push($output , "Kata dasar : ".$trim_pref81." ");
										 array_push($output , "Pola kanonik : -  ");
										  
										 }
									 }
			 
								 // -an
								 elseif(preg_match("/$suf1$/", substr($trim_pref3, 0))){
									 $trim_suf1 = preg_replace("/$suf1$/", '', $trim_pref3);
									 $fonem_suf1 = preg_replace($konsonan, 'K', $trim_suf1);
									 $fonem_suf1kv = preg_replace($vokal, 'V', $fonem_suf1);
									 
									 // Cek pola kanonik
									 if(in_array($fonem_suf1kv, $rules)){
										 array_push($output , "Kata dasar: ".$katajadi[$i]." ");
										 array_push($output , "Pola fonem: ".$kv." ");
										 array_push($output , "Prefiks : ".$pref3." ");
										 array_push($output , "Infiks : -  ");
										 array_push($output , "Sufiks : ".$suf1." ");
										 array_push($output , "Confiks : ".$pref3."+ - +".$suf1." ");
										 array_push($output , "Ambifiks :  -  ");
										 array_push($output , "Kata dasar : ".$trim_suf1." ");
										 array_push($output , "Pola kanonik : ".$fonem_suf1kv." ");
										  
										 }
									 else{
										 array_push($output , "Kata dasar: ".$katajadi[$i]." ");
										 array_push($output , "Pola fonem: ".$kv." ");
										 array_push($output , "Prefiks : ".$pref3." ");
										 array_push($output , "Infiks : -  ");
										 array_push($output , "Sufiks : ".$suf1." ");
										 array_push($output , "Confiks : ".$pref3."+ - +".$suf1." ");
										 array_push($output , "Ambifiks :  -  ");
										 array_push($output , "Kata dasar : ".$trim_suf1." ");
										 array_push($output , "Pola kanonik : -  ");
										  
										 }
									 }
			 
								 // -keun
								 elseif(preg_match("/$suf3$/", substr($trim_pref3, -4))){
									 $trim_suf3 = preg_replace("/$suf3$/", '', $trim_pref3);
									 $fonem_suf3 = preg_replace($konsonan, 'K', $trim_suf3);
									 $fonem_suf3kv = preg_replace($vokal, 'V', $fonem_suf3);
									 
									 // Cek pola kanonik
									 if(in_array($fonem_suf3kv, $rules)){
										 array_push($output , "Kata dasar: ".$katajadi[$i]." ");
										 array_push($output , "Pola fonem: ".$kv." ");
										 array_push($output , "Prefiks : ".$pref3." ");
										 array_push($output , "Infiks : -  ");
										 array_push($output , "Sufiks : ".$suf3." ");
										 array_push($output , "Confiks : -  ");
										 array_push($output , "Ambifiks :  ".$pref3."+ - +".$suf3." ");
										 array_push($output , "Kata dasar : ".$trim_suf3." ");
										 array_push($output , "Pola kanonik : ".$fonem_suf3kv." ");
										  
										 }
									 else{
										 array_push($output , "Kata dasar: ".$katajadi[$i]." ");
										 array_push($output , "Pola fonem: ".$kv." ");
										 array_push($output , "Prefiks : ".$pref3." ");
										 array_push($output , "Infiks : -  ");
										 array_push($output , "Sufiks : ".$suf3." ");
										 array_push($output , "Confiks : -  ");
										 array_push($output , "Ambifiks : ".$pref3."+ - +".$suf3." ");
										 array_push($output , "Kata dasar : ".$trim_pref3." ");
										 array_push($output , "Pola kanonik : -  ");
										  
										 }
									 }
			 
								 else{
									 // Cek pola kanonik
									 if(in_array($fonem_pref3kv, $rules)){
										 array_push($output , "Kata dasar: ".$katajadi[$i]." ");
										 array_push($output , "Pola fonem: ".$kv." ");
										 array_push($output , "Prefiks : ".$pref3." ");
										 array_push($output , "Infiks : -  ");
										 array_push($output , "Sufiks : -  ");
										 array_push($output , "Confiks : -  ");
										 array_push($output , "Ambifiks : -  ");
										 array_push($output , "Kata dasar : ".$trim_pref3." ");
										 array_push($output , "Pola kanonik : ".$fonem_pref3kv." ");
										  
										 }
									 else{
										 array_push($output , "Kata dasar: ".$katajadi[$i]." ");
										 array_push($output , "Pola fonem: ".$kv." ");
										 array_push($output , "Prefiks : ".$pref3." ");
										 array_push($output , "Infiks : -  ");
										 array_push($output , "Sufiks : -  ");
										 array_push($output , "Confiks : -  ");
										 array_push($output , "Ambifiks : -  ");
										 array_push($output , "Kata dasar : ".$trim_suf3." ");
										 array_push($output , "Pola kanonik : -  ");
										  
										 }
									 }
								 }
			 
							 // (pa)ng-di-pi(ka)-an|-na|-keun
							 // pa-
							 if(preg_match("/^$pref5/", substr($katajadi[$i], 0))){
								 $trim_pref5 = preg_replace("/^$pref5/", '', $katajadi[$i]);
								 $fonem_pref5 = preg_replace($konsonan, 'K', $trim_pref5);
								 $fonem_pref5kv = preg_replace($vokal, 'V', $fonem_pref5);					
			 
								 // -an
								 if(preg_match("/$suf1$/", substr($trim_pref5, -2))){
									 $trim_pref54 = preg_replace("/$suf1$/", '', $trim_pref5);
									 $fonem_pref54 = preg_replace($konsonan, 'K', $trim_pref54);
									 $fonem_pref54kv = preg_replace($vokal, 'V', $fonem_pref54);
			 
									 // Cek pola kanonik
									 if(in_array($fonem_pref54kv, $rules)){
										 array_push($output , "Kata awal : ".$katajadi[$i].' ');
										 array_push($output , "Pola fonem : ".$kv.' ');
										 array_push($output , "Prefiks : ".$pref5." ");
										 array_push($output , "Infiks : -  ");
										 array_push($output , "Sufiks :  ".$suf1." ");
										 array_push($output , "Confiks :  ".$pref5."+ - +".$suf1." ");
										 array_push($output , "Ambifiks : -  ");
										 array_push($output , "Kata dasar : ".$trim_pref54.' ');
										 array_push($output , "Pola kanonik : ".$fonem_pref54kv." ");
										  
										 }
									 else{	
										 array_push($output , "Kata awal : ".$katajadi[$i].' ');
										 array_push($output , "Pola fonem : ".$kv.' ');
										 array_push($output , "Prefiks : ".$pref5." ");
										 array_push($output , "Infiks : -  ");
										 array_push($output , "Sufiks :  ".$suf1." ");
										 array_push($output , "Confiks :  ".$pref5."+ - +".$suf1." ");
										 array_push($output , "Ambifiks : -  ");
										 array_push($output , "Kata dasar : ".$trim_pref54.' ');
										 array_push($output , "Pola kanonik : -  ");
										  
										 }
									 }
			 
								 // -na
								 elseif(preg_match("/$suf4$/", substr($trim_pref5, -2))){
									 $trim_suf4 = preg_replace("/$suf4$/", '', $trim_pref5);
									 $fonem_suf4 = preg_replace($konsonan, 'K', $trim_suf4);
									 $fonem_suf4kv = preg_replace($vokal, 'V', $fonem_suf4);
			 
									 // Cek pola kanonik
									 if(in_array($fonem_suf4kv, $rules)){
										 array_push($output , "Kata awal : ".$katajadi[$i].' ');
										 array_push($output , "Pola fonem : ".$kv.' ');
										 array_push($output , "Prefiks : ".$pref5." ");
										 array_push($output , "Infiks : -  ");
										 array_push($output , "Sufiks :  ".$suf4." ");
										 array_push($output , "Confiks :  ".$pref5."+ - +".$suf4." ");
										 array_push($output , "Ambifiks : -  ");
										 array_push($output , "Kata dasar : ".$trim_suf4.' ');
										 array_push($output , "Pola kanonik : ".$fonem_suf4kv." ");
										  
										 }
									 else{	
										 array_push($output , "Kata awal : ".$katajadi[$i].' ');
										 array_push($output , "Pola fonem : ".$kv.' ');
										 array_push($output , "Prefiks : ".$pref5." ");
										 array_push($output , "Infiks : -  ");
										 array_push($output , "Sufiks :  ".$suf4." ");
										 array_push($output , "Confiks :  ".$pref5."+ - +".$suf4." ");
										 array_push($output , "Ambifiks : -  ");
										 array_push($output , "Kata dasar : ".$trim_suf4.' ');
										 array_push($output , "Pola kanonik : -  ");
										  
										 }
									 }
			 
								 // -keun
								 elseif(preg_match("/$suf3$/", substr($trim_pref5, -4))){
									 $trim_suf3 = preg_replace("/$suf3$/", '', $trim_pref5);
									 $fonem_suf3 = preg_replace($konsonan, 'K', $trim_suf3);
									 $fonem_suf3kv = preg_replace($vokal, 'V', $fonem_suf3);
			 
									 // Cek pola kanonik
									 if(in_array($fonem_suf3kv, $rules)){
										 array_push($output , "Kata awal : ".$katajadi[$i].' ');
										 array_push($output , "Pola fonem : ".$kv.' ');
										 array_push($output , "Prefiks : ".$pref5." ");
										 array_push($output , "Infiks : -  ");
										 array_push($output , "Sufiks :  ".$suf3." ");
										 array_push($output , "Confiks :  ".$pref5."+ - +".$suf3." ");
										 array_push($output , "Ambifiks : -  ");
										 array_push($output , "Kata dasar : ".$trim_suf3.' ');
										 array_push($output , "Pola kanonik : ".$fonem_suf3kv." ");
										  
										 }
									 else{	
										 array_push($output , "Kata awal : ".$katajadi[$i].' ');
										 array_push($output , "Pola fonem : ".$kv.' ');
										 array_push($output , "Prefiks : ".$pref5." ");
										 array_push($output , "Infiks : -  ");
										 array_push($output , "Sufiks :  ".$suf3." ");
										 array_push($output , "Confiks :  ".$pref5."+ - +".$suf3." ");
										 array_push($output , "Ambifiks : -  ");
										 array_push($output , "Kata dasar : ".$trim_suf3.' ');
										 array_push($output , "Pola kanonik : -  ");
										  
										 }
									 }
								 
								 // -ka
								 {
									 $trim_suf3 = preg_replace("/^$pref5/", '', $trim_pref5);
									 $fonem_suf3 = preg_replace($konsonan, 'K', $trim_suf3);
									 $fonem_suf3kv = preg_replace($vokal, 'V', $fonem_suf3);
			 
									 // Cek pola kanonik
									 if(in_array($fonem_suf3kv, $rules)){
										 array_push($output , "Kata awal : ".$katajadi[$i].' ');
										 array_push($output , "Pola fonem : ".$kv.' ');
										 array_push($output , "Prefiks : ".$pref5." ");
										 array_push($output , "Infiks : -  ");
										 array_push($output , "Sufiks : -  ");
										 array_push($output , "Confiks :  ");
										 array_push($output , "Ambifiks : -  ");
										 array_push($output , "Kata dasar : ".$trim_suf3.' ');
										 array_push($output , "Pola kanonik : ".$fonem_suf3kv." ");
										  
										 }
									 else{	
										 array_push($output , "Kata awal : ".$katajadi[$i].' ');
										 array_push($output , "Pola fonem : ".$kv.' ');
										 array_push($output , "Prefiks : ".$pref5." ");
										 array_push($output , "Infiks : -  ");
										 array_push($output , "Sufiks : -  ");
										 array_push($output , "Confiks :  ");
										 array_push($output , "Ambifiks : -  ");
										 array_push($output , "Kata dasar : ".$trim_suf3.' ');
										 array_push($output , "Pola kanonik : -  ");
										  
										 }
									 }
			 
								 }
			 
							 // pi(ka)-eun
							 // pi-
							 if(preg_match("/^$pref8/", substr($katajadi[$i], 0))){
								 $trim_pref81 = preg_replace("/^$pref8/", '', $katajadi[$i]);
								 $fonem_pref81 = preg_replace($konsonan, 'K', $trim_pref81);
								 $fonem_pref81kv = preg_replace($vokal, 'V', $fonem_pref81);
			 
								 // ka-
								 if(preg_match("/^$pref3/", substr($trim_pref81, 0))){
									 $trim_pref82 = preg_replace("/^$pref3/", '', $trim_pref81);
									 $fonem_pref82 = preg_replace($konsonan, 'K', $trim_pref82);
									 $fonem_pref82kv = preg_replace($vokal, 'V', $fonem_pref82);
			 
									 // -eun
									 if(preg_match("/$suf2$/", substr($trim_pref82, -3))){
										 $trim_pref83 = preg_replace("/$suf2$/", '', $trim_pref82);
										 $fonem_pref83 = preg_replace($konsonan, 'K', $trim_pref83);
										 $fonem_pref83kv = preg_replace($vokal, 'V', $fonem_pref83);
			 
										 // Cek pola kanonik
										 if(in_array($fonem_pref83kv, $rules)){
											 array_push($output , "Kata dasar: ".$katajadi[$i]." ");
											 array_push($output , "Pola fonem: ".$kv." ");
											 array_push($output , "Prefiks1 : ".$pref8." ");
											 array_push($output , "Prefiks2 : ".$pref3." ");
											 array_push($output , "Infiks : -  ");
											 array_push($output , "Sufiks : ".$suf2." ");
											 array_push($output , "Confiks : ".$pref8.$pref3."+ - +".$suf2." ");
											 array_push($output , "Ambifiks :  -  ");
											 array_push($output , "Kata dasar : ".$trim_pref83." ");
											 array_push($output , "Pola kanonik : ".$fonem_pref83kv." ");
											  
											 }
										 else{
											 array_push($output , "Kata dasar: ".$katajadi[$i]." ");
											 array_push($output , "Pola fonem: ".$kv." ");
											 array_push($output , "Prefiks1 : ".$pref8." ");
											 array_push($output , "Prefiks2 : ".$pref3." ");
											 array_push($output , "Infiks : -  ");
											 array_push($output , "Sufiks : ".$suf2." ");
											 array_push($output , "Confiks : ".$pref8.$pref3."+ - +".$suf2." ");
											 array_push($output , "Ambifiks :  -  ");
											 array_push($output , "Kata dasar : ".$trim_pref83." ");
											 array_push($output , "Pola kanonik : -  ");
											  
											 }
										 }
									 // pi-ka
									 else{
										 // Cek pola kanonik
										 if(in_array($fonem_pref82kv, $rules)){
											 array_push($output , "Kata dasar: ".$katajadi[$i]." ");
											 array_push($output , "Pola fonem: ".$kv." ");
											 array_push($output , "Prefiks1 : ".$pref8." ");
											 array_push($output , "Prefiks2 : ".$pref3." ");
											 array_push($output , "Infiks : -  ");
											 array_push($output , "Sufiks : -  ");
											 array_push($output , "Confiks : ".$pref8.$pref3." ");
											 array_push($output , "Ambifiks :  -  ");
											 array_push($output , "Kata dasar : ".$trim_pref82." ");
											 array_push($output , "Pola kanonik : ".$fonem_pref82kv." ");
											  
											 }
										 else{
											 array_push($output , "Kata dasar: ".$katajadi[$i]." ");
											 array_push($output , "Pola fonem: ".$kv." ");
											 array_push($output , "Prefiks1 : ".$pref8." ");
											 array_push($output , "Prefiks2 : ".$pref3." ");
											 array_push($output , "Infiks : -  ");
											 array_push($output , "Sufiks : -  ");
											 array_push($output , "Confiks : ".$pref8.$pref3." ");
											 array_push($output , "Ambifiks :  -  ");
											 array_push($output , "Kata dasar : ".$trim_pref82." ");
											 array_push($output , "Pola kanonik : -  ");
											  
											 }
										 }
									 }
			 
								 elseif(preg_match("/$suf2$/", substr($trim_pref81, -3))){
										 $trim_suf2 = preg_replace("/$suf2$/", '', $trim_pref81);
										 $fonem_suf2 = preg_replace($konsonan, 'K', $trim_suf2);
										 $fonem_suf2kv = preg_replace($vokal, 'V', $fonem_suf2);
			 
										 // Cek pola kanonik
										 if(in_array($fonem_suf2kv, $rules)){
											 array_push($output , "Kata dasar: ".$katajadi[$i]." ");
											 array_push($output , "Pola fonem: ".$kv." ");
											 array_push($output , "Prefiks : ".$pref8." ");
											 array_push($output , "Infiks : -  ");
											 array_push($output , "Sufiks : ".$suf2." ");
											 array_push($output , "Confiks : ".$pref8."+ - +".$suf2." ");
											 array_push($output , "Ambifiks :  -  ");
											 array_push($output , "Kata dasar : ".$trim_suf2." ");
											 array_push($output , "Pola kanonik : ".$fonem_suf2kv." ");
											  
											 }
										 else{
											 array_push($output , "Kata dasar: ".$katajadi[$i]." ");
											 array_push($output , "Pola fonem: ".$kv." ");
											 array_push($output , "Prefiks : ".$pref8." ");
											 array_push($output , "Infiks : -  ");
											 array_push($output , "Sufiks : ".$suf2." ");
											 array_push($output , "Confiks : ".$pref8."+ - +".$suf2." ");
											 array_push($output , "Ambifiks :  -  ");
											 array_push($output , "Kata dasar : ".$trim_suf2." ");
											 array_push($output , "Pola kanonik : -  ");
											  
											 }
										 }
								 else{
									 // -an
									 if(preg_match("/$suf1$/", substr($trim_pref81, -2))){
										 $trim_suf1 = preg_replace("/$suf1$/", '', $trim_pref81);
										 $fonem_suf1 = preg_replace($konsonan, 'K', $trim_suf1);
										 $fonem_suf1kv = preg_replace($vokal, 'V', $fonem_suf1);
			 
										 // Cek pola kanonik
										 if(in_array($fonem_suf1kv, $rules)){
											 array_push($output , "Kata dasar: ".$katajadi[$i]." ");
											 array_push($output , "Pola fonem: ".$kv." ");
											 array_push($output , "Prefiks : ".$pref8." ");
											 array_push($output , "Infiks : -  ");
											 array_push($output , "Sufiks : ".$suf1." ");
											 array_push($output , "Confiks : ".$pref8."+ - +".$suf1." ");
											 array_push($output , "Ambifiks :  -  ");
											 array_push($output , "Kata dasar : ".$trim_suf1." ");
											 array_push($output , "Pola kanonik : ".$fonem_suf1kv." ");
											  
											 }
										 else{
											 array_push($output , "Kata dasar: ".$katajadi[$i]." ");
											 array_push($output , "Pola fonem: ".$kv." ");
											 array_push($output , "Prefiks : ".$pref8." ");
											 array_push($output , "Infiks : -  ");
											 array_push($output , "Sufiks : ".$suf1." ");
											 array_push($output , "Confiks : ".$pref8."+ - +".$suf1." ");
											 array_push($output , "Ambifiks :  -  ");
											 array_push($output , "Kata dasar : ".$trim_suf1." ");
											 array_push($output , "Pola kanonik : -  ");
											  
											 }
										 }	
									 }
								 }
			 
							 // (sa)ng-
							 // sa-
							 if(preg_match("/^$pref9/", substr($katajadi[$i], 0))){
								 $trim_pref9 = preg_replace("/^$pref9/", '', $katajadi[$i]);
								 $fonem_pref9 = preg_replace($konsonan, 'K', $trim_pref9);
								 $fonem_pref9kv = preg_replace($vokal, 'V', $fonem_pref9);
			 
								 // sang-
								 if(preg_match("/^$pref10/", substr($katajadi[$i], 0))){
									 $trim_pref12 = preg_replace("/^$pref10/", '', $katajadi[$i]);
									 $fonem_pref12 = preg_replace($konsonan, 'K', $trim_pref12);
									 $fonem_pref12kv = preg_replace($vokal, 'V', $fonem_pref12);
										 
									 // Cek pola kanonik
									 if(in_array($fonem_pref12kv, $rules)){
										 array_push($output , "Kata dasar: ".$katajadi[$i]." ");
										 array_push($output , "Pola fonem: ".$kv." ");
										 array_push($output , "Prefiks : ".$pref10." ");
										 array_push($output , "Infiks : -  ");
										 array_push($output , "Sufiks : -  ");
										 array_push($output , "Confiks : -  ");
										 array_push($output , "Ambifiks :  -  ");
										 array_push($output , "Kata dasar : ".$trim_pref12." ");
										 array_push($output , "Pola kanonik : ".$fonem_pref12kv." ");
										  
										 }
									 else{
										 array_push($output , "Kata dasar: ".$katajadi[$i]." ");
										 array_push($output , "Pola fonem: ".$kv." ");
										 array_push($output , "Prefiks : ".$pref10." ");
										 array_push($output , "Infiks : -  ");
										 array_push($output , "Sufiks : -  ");
										 array_push($output , "Confiks : -  ");
										 array_push($output , "Ambifiks :  -  ");
										 array_push($output , "Kata dasar : ".$trim_pref12." ");
										 array_push($output , "Pola kanonik : -  ");
										  
									 }
								 }
			 
								 // -eun
								 elseif(preg_match("/$suf2$/", substr($trim_pref9, -3))){
										 $trim_suf2 = preg_replace("/$suf2$/", '', $trim_pref9);
										 $fonem_suf2 = preg_replace($konsonan, 'K', $trim_suf2);
										 $fonem_suf2kv = preg_replace($vokal, 'V', $fonem_suf2);
									 // Cek pola kanonik
									 if(in_array($fonem_suf2kv, $rules)){
										 array_push($output , "Kata dasar: ".$katajadi[$i]." ");
										 array_push($output , "Pola fonem: ".$kv." ");
										 array_push($output , "Prefiks : ".$pref9." ");
										 array_push($output , "Infiks : -  ");
										 array_push($output , "Sufiks : ".$suf2." ");
										 array_push($output , "Confiks : ".$pref9."+ - + ".$suf2." ");
										 array_push($output , "Ambifiks :  -  ");
										 array_push($output , "Kata dasar : ".$trim_suf2." ");
										 array_push($output , "Pola kanonik : ".$fonem_suf2kv." ");
										  
										 }
									 else{
										 array_push($output , "Kata dasar: ".$katajadi[$i]." ");
										 array_push($output , "Pola fonem: ".$kv." ");
										 array_push($output , "Prefiks : ".$pref9." ");
										 array_push($output , "Infiks : -  ");
										 array_push($output , "Sufiks : ".$suf2." ");
										 array_push($output , "Confiks : ".$pref9."+ - + ".$suf2." ");
										 array_push($output , "Ambifiks :  -  ");
										 array_push($output , "Kata dasar : ".$trim_suf2." ");
										 array_push($output , "Pola kanonik : -  ");
										  
										 }
									 }
								 // -na
								 elseif(preg_match("/$suf4$/", substr($trim_pref9, -2))){
									 $trim_suf4 = preg_replace("/$suf4$/", '', $trim_pref9);
									 $fonem_suf4 = preg_replace($konsonan, 'K', $trim_suf4);
									 $fonem_suf4kv = preg_replace($vokal, 'V', $fonem_suf4);
									 
									 // Cek pola kanonik
									 if(in_array($fonem_suf4kv, $rules)){
										 array_push($output , "Kata dasar: ".$katajadi[$i]." ");
										 array_push($output , "Pola fonem: ".$kv." ");
										 array_push($output , "Prefiks : ".$pref9." ");
										 array_push($output , "Infiks : -  ");
										 array_push($output , "Sufiks : ".$suf4." ");
										 array_push($output , "Confiks : ".$pref9."+ - + ".$suf4." ");
										 array_push($output , "Ambifiks :  -  ");
										 array_push($output , "Kata dasar : ".$trim_suf4." ");
										 array_push($output , "Pola kanonik : ".$fonem_suf4kv." ");
										  
										 }
									 else{
										 array_push($output , "Kata dasar: ".$katajadi[$i]." ");
										 array_push($output , "Pola fonem: ".$kv." ");
										 array_push($output , "Prefiks : ".$pref9." ");
										 array_push($output , "Infiks : -  ");
										 array_push($output , "Sufiks : ".$suf4." ");
										 array_push($output , "Confiks : ".$pref9."+ - + ".$suf4." ");
										 array_push($output , "Ambifiks :  -  ");
										 array_push($output , "Kata dasar : ".$trim_suf4." ");
										 array_push($output , "Pola kanonik : -  ");
										  
										 }
									 }
								 else{
									 // Cek pola kanonik
									 if(in_array($fonem_pref9kv, $rules)){
										 array_push($output , "Kata dasar: ".$katajadi[$i]." ");
										 array_push($output , "Pola fonem: ".$kv." ");
										 array_push($output , "Prefiks : ".$pref9." ");
										 array_push($output , "Infiks : -  ");
										 array_push($output , "Sufiks : -  ");
										 array_push($output , "Confiks : -  ");
										 array_push($output , "Ambifiks :  -  ");
										 array_push($output , "Kata dasar : ".$trim_pref9." ");
										 array_push($output , "Pola kanonik : ".$fonem_pref9kv." ");
										  
										 }
									 else{
										 array_push($output , "Kata dasar: ".$katajadi[$i]." ");
										 array_push($output , "Pola fonem: ".$kv." ");
										 array_push($output , "Prefiks : ".$pref9." ");
										 array_push($output , "Infiks : -  ");
										 array_push($output , "Sufiks : -  ");
										 array_push($output , "Confiks : -  ");
										 array_push($output , "Ambifiks :  -  ");
										 array_push($output , "Kata dasar : ".$trim_pref9." ");
										 array_push($output , "Pola kanonik : -  ");
										  
										 }
									 }
								 }
			 
							 // (ti)ng
							 if(preg_match("/^$pref4/", substr($katajadi[$i], 0))){
								 $trim_pref4 = preg_replace("/^$pref4/", '', $katajadi[$i]);
								 $fonem_pref4 = preg_replace($konsonan, 'K', $trim_pref4);
								 $fonem_pref4kv = preg_replace($vokal, 'V', $fonem_pref4);
			 
								 if(preg_match("/^$pref12/", substr($katajadi[$i], 0))){
									 $trim_pref12 = preg_replace("/^$pref12/", '', $katajadi[$i]);
									 $fonem_pref12 = preg_replace($konsonan, 'K', $trim_pref12);
									 $fonem_pref12kv = preg_replace($vokal, 'V', $fonem_pref12);
			 
									 if(preg_match("/^$inf1/", substr($trim_pref12, 0))){
										 $trim_inf1 = preg_replace("/^$inf1/", '', $trim_pref12);
										 $fonem_inf1 = preg_replace($konsonan, 'K', $trim_inf1);
										 $fonem_inf1kv = preg_replace($vokal, 'V', $fonem_inf1);
			 
										 // Cek pola kanonik
										 if(in_array($fonem_inf1kv, $rules)){
											 array_push($output , "Kata dasar: ".$katajadi[$i]." ");
											 array_push($output , "Pola fonem: ".$kv." ");
											 array_push($output , "Prefiks : ".$pref12." ");
											 array_push($output , "Infiks : ".$inf1." ");
											 array_push($output , "Sufiks : -  ");
											 array_push($output , "Confiks : -  ");
											 array_push($output , "Ambifiks :  -  ");
											 array_push($output , "Kata dasar : ".$trim_inf1." ");
											 array_push($output , "Pola kanonik : ".$fonem_inf1kv." ");
											  
											 }
										 else{
											 array_push($output , "Kata dasar: ".$katajadi[$i]." ");
											 array_push($output , "Pola fonem: ".$kv." ");
											 array_push($output , "Prefiks : ".$pref12." ");
											 array_push($output , "Infiks : ".$inf1." ");
											 array_push($output , "Sufiks : -  ");
											 array_push($output , "Confiks : -  ");
											 array_push($output , "Ambifiks :  -  ");
											 array_push($output , "Kata dasar : ".$trim_inf1." ");
											 array_push($output , "Pola kanonik : -  ");
											  
											 }							
										 }
									 else{
									 // Cek pola kanonik
									 if(in_array($fonem_pref12kv, $rules)){
										 array_push($output , "Kata dasar: ".$katajadi[$i]." ");
										 array_push($output , "Pola fonem: ".$kv." ");
										 array_push($output , "Prefiks : ".$pref12." ");
										 array_push($output , "Infiks : -  ");
										 array_push($output , "Sufiks : -  ");
										 array_push($output , "Confiks : -  ");
										 array_push($output , "Ambifiks :  -  ");
										 array_push($output , "Kata dasar : ".$trim_pref12." ");
										 array_push($output , "Pola kanonik : ".$fonem_pref12kv." ");
										  
										 }
									 else{
										 array_push($output , "Kata dasar: ".$katajadi[$i]." ");
										 array_push($output , "Pola fonem: ".$kv." ");
										 array_push($output , "Prefiks : ".$pref12." ");
										 array_push($output , "Infiks : -  ");
										 array_push($output , "Sufiks : -  ");
										 array_push($output , "Confiks : -  ");
										 array_push($output , "Ambifiks :  -  ");
										 array_push($output , "Kata dasar : ".$trim_pref12." ");
										 array_push($output , "Pola kanonik : -  ");
										  
										 }
									 }
								 }
								 else{
									 // Cek pola kanonik
									 if(in_array($fonem_pref4kv, $rules)){
										 array_push($output , "Kata dasar: ".$katajadi[$i]." ");
										 array_push($output , "Pola fonem: ".$kv." ");
										 array_push($output , "Prefiks : ".$pref4." ");
										 array_push($output , "Infiks : -  ");
										 array_push($output , "Sufiks : -  ");
										 array_push($output , "Confiks : -  ");
										 array_push($output , "Ambifiks :  -  ");
										 array_push($output , "Kata dasar : ".$trim_pref4." ");
										 array_push($output , "Pola kanonik : ".$fonem_pref4kv." ");
										  
										 }
									 else{
										 array_push($output , "Kata dasar: ".$katajadi[$i]." ");
										 array_push($output , "Pola fonem: ".$kv." ");
										 array_push($output , "Prefiks : ".$pref4." ");
										 array_push($output , "Infiks : -  ");
										 array_push($output , "Sufiks : -  ");
										 array_push($output , "Confiks : -  ");
										 array_push($output , "Ambifiks :  -  ");
										 array_push($output , "Kata dasar : ".$trim_pref4." ");
										 array_push($output , "Pola kanonik : -  ");
										  
										 }
									 }
								 }
			 
							 // Sufiks
			 
			 
							 // -an
			 
							 // -eun
			 
							 // -keun
			 
							 // -na
			 
							 // (n)ing
			 
			 
							 // Infiks
			 
							 // Cek infiks ar-
							 if(preg_match("/$inf1/", substr($katajadi[$i], 0))){
								 $trim_inf1 = preg_replace("/^$inf1/", '', $katajadi[$i]);
								 $fonem_inf1 = preg_replace($konsonan, 'K', $trim_inf1);
								 $fonem_inf1kv = preg_replace($vokal, 'V', $fonem_inf1);
			 
								 // Cek pola kanonik
								 if(in_array($fonem_inf1kv, $rules)){
									 array_push($output , "Kata dasar: ".$katajadi[$i]." ");
									 array_push($output , "Pola fonem: ".$kv." ");
									 array_push($output , "Prefiks : -  ");
									 array_push($output , "Infiks : ".$inf1." ");
									 array_push($output , "Sufiks : -  ");
									 array_push($output , "Confiks : -  ");
									 array_push($output , "Ambifiks :  -  ");
									 array_push($output , "Kata dasar : ".$trim_inf1." ");
									 array_push($output , "Pola kanonik : ".$fonem_inf1kv." ");
									  
									 }
								 else{
									 array_push($output , "Kata dasar: ".$katajadi[$i]." ");
									 array_push($output , "Pola fonem: ".$kv." ");
									 array_push($output , "Prefiks : -  ");
									 array_push($output , "Infiks : ".$inf1." ");
									 array_push($output , "Sufiks : -  ");
									 array_push($output , "Confiks : -  ");
									 array_push($output , "Ambifiks :  -  ");
									 array_push($output , "Kata dasar : ".$trim_inf1." ");
									 array_push($output , "Pola kanonik : -  ");
									  
									 }
								 }
			 
							 // Cek prefiks al-
							 if(preg_match("/$inf2/", substr($katajadi[$i], 0))){
								 $trim_inf2 = preg_replace("/$inf2/", '', $katajadi[$i]);
								 $fonem_inf2 = preg_replace($konsonan, 'K', $trim_inf2);
								 $fonem_inf2kv = preg_replace($vokal, 'V', $fonem_inf2);
			 
								 // Cek pola kanonik
								 if(in_array($fonem_inf2kv, $rules)){
									 array_push($output , "Kata dasar: ".$katajadi[$i]." ");
									 array_push($output , "Pola fonem: ".$kv." ");
									 array_push($output , "Prefiks : -  ");
									 array_push($output , "Infiks : ".$inf2." ");
									 array_push($output , "Sufiks : -  ");
									 array_push($output , "Confiks : -  ");
									 array_push($output , "Ambifiks :  -  ");
									 array_push($output , "Kata dasar : ".$trim_inf2." ");
									 array_push($output , "Pola kanonik : ".$fonem_inf2kv." ");
									  
									 }
								 else{
									 array_push($output , "Kata dasar: ".$katajadi[$i]." ");
									 array_push($output , "Pola fonem: ".$kv." ");
									 array_push($output , "Prefiks : -  ");
									 array_push($output , "Infiks : ".$inf2." ");
									 array_push($output , "Sufiks : -  ");
									 array_push($output , "Confiks : -  ");
									 array_push($output , "Ambifiks :  -  ");
									 array_push($output , "Kata dasar : ".$trim_inf2." ");
									 array_push($output , "Pola kanonik : -  ");
									  
									 }
								 }
			 
							 // Cek prefiks um-
							 if(preg_match("/$inf3/", substr($katajadi[$i], 0))){
								 $trim_inf3 = preg_replace("/$inf3/", '', $katajadi[$i]);
								 $fonem_inf3 = preg_replace($konsonan, 'K', $trim_inf3);
								 $fonem_inf3kv = preg_replace($vokal, 'V', $fonem_inf3);
			 
								 // Cek pola kanonik
								 if(in_array($fonem_inf3kv, $rules)){
									 array_push($output , "Kata dasar: ".$katajadi[$i]." ");
									 array_push($output , "Pola fonem: ".$kv." ");
									 array_push($output , "Prefiks : -  ");
									 array_push($output , "Infiks : ".$inf3." ");
									 array_push($output , "Sufiks : -  ");
									 array_push($output , "Confiks : -  ");
									 array_push($output , "Ambifiks :  -  ");
									 array_push($output , "Kata dasar : ".$trim_inf3." ");
									 array_push($output , "Pola kanonik : ".$fonem_inf3kv." ");
									  
									 }
								 else{
									 array_push($output , "Kata dasar: ".$katajadi[$i]." ");
									 array_push($output , "Pola fonem: ".$kv." ");
									 array_push($output , "Prefiks : -  ");
									 array_push($output , "Infiks : ".$inf3." ");
									 array_push($output , "Sufiks : -  ");
									 array_push($output , "Confiks : -  ");
									 array_push($output , "Ambifiks :  -  ");
									 array_push($output , "Kata dasar : ".$trim_inf3." ");
									 array_push($output , "Pola kanonik : -  ");
									  
									 }
								 }
			 
							 // Cek prefiks in-
							 if(preg_match("/$inf4/", substr($katajadi[$i], 0))){
								 $trim_inf4 = preg_replace("/$inf4/", '', $katajadi[$i]);
								 $fonem_inf4 = preg_replace($konsonan, 'K', $trim_inf4);
								 $fonem_inf4kv = preg_replace($vokal, 'V', $fonem_inf4);
								 // Cek pola kanonik
								 if(in_array($fonem_inf4kv, $rules)){
									 array_push($output , "Kata dasar: ".$katajadi[$i]." ");
									 array_push($output , "Pola fonem: ".$kv." ");
									 array_push($output , "Prefiks : -  ");
									 array_push($output , "Infiks : ".$inf4." ");
									 array_push($output , "Sufiks : -  ");
									 array_push($output , "Confiks : -  ");
									 array_push($output , "Ambifiks :  -  ");
									 array_push($output , "Kata dasar : ".$trim_inf4." ");
									 array_push($output , "Pola kanonik : ".$fonem_inf4kv." ");
									  
									 }
								 else{
									 array_push($output , "Kata dasar: ".$katajadi[$i]." ");
									 array_push($output , "Pola fonem: ".$kv." ");
									 array_push($output , "Prefiks : -  ");
									 array_push($output , "Infiks : ".$inf4." ");
									 array_push($output , "Sufiks : -  ");
									 array_push($output , "Confiks : -  ");
									 array_push($output , "Ambifiks :  -  ");
									 array_push($output , "Kata dasar : ".$trim_inf4." ");
									 array_push($output , "Pola kanonik : -  ");
									  
									 }
								 }
			 
							 // Cek infiks ar-
							 if(preg_match("/^$inf1/", substr($katajadi[$i], 0))){
								 $trim_inf1 = preg_replace("/^$inf1/", '', $katajadi[$i]);
								 $fonem_inf1 = preg_replace($konsonan, 'K', $trim_inf1);
								 $fonem_inf1kv = preg_replace($vokal, 'V', $fonem_inf1);
								 
			 
								 if(preg_match("/$inf1/", substr($katajadi[$i], 0))){
									 $trim_inf1 = preg_replace("/$inf1/", '', $katajadi[$i]);
									 $fonem_inf1 = preg_replace($konsonan, 'K', $trim_inf1);
									 $fonem_inf1kv = preg_replace($vokal, 'V', $fonem_inf1);
			 
									 // Cek pola kanonik
									 if(in_array($fonem_inf1kv, $rules)){
										 array_push($output , "Kata dasar: ".$katajadi[$i]." ");
										 array_push($output , "Pola fonem: ".$kv." ");
										 array_push($output , "Prefiks : -  ");
										 array_push($output , "Infiks : ".$inf1." ");
										 array_push($output , "Sufiks : -  ");
										 array_push($output , "Confiks : -  ");
										 array_push($output , "Ambifiks :  -  ");
										 array_push($output , "Kata dasar : ".$trim_inf1." ");
										 array_push($output , "Pola kanonik : ".$fonem_inf1kv." ");
										  
										 }
									 else{
										 array_push($output , "Kata dasar: ".$katajadi[$i]." ");
										 array_push($output , "Pola fonem: ".$kv." ");
										 array_push($output , "Prefiks : -  ");
										 array_push($output , "Infiks : ".$inf1." ");
										 array_push($output , "Sufiks : -  ");
										 array_push($output , "Confiks : -  ");
										 array_push($output , "Ambifiks :  -  ");
										 array_push($output , "Kata dasar : ".$trim_inf1." ");
										 array_push($output , "Pola kanonik : -  ");
										  
										 }
									 }
								 else{
									 // Cek pola kanonik
									 if(in_array($fonem_inf1kv, $rules)){
										 array_push($output , "Kata dasar: ".$katajadi[$i]." ");
										 array_push($output , "Pola fonem: ".$kv." ");
										 array_push($output , "Prefiks : -  ");
										 array_push($output , "Infiks : ".$inf1." ");
										 array_push($output , "Sufiks : -  ");
										 array_push($output , "Confiks : -  ");
										 array_push($output , "Ambifiks :  -  ");
										 array_push($output , "Kata dasar : ".$trim_inf1." ");
										 array_push($output , "Pola kanonik : ".$fonem_inf1kv." ");
										  
										 }
									 else{
										 array_push($output , "Kata dasar: ".$katajadi[$i]." ");
										 array_push($output , "Pola fonem: ".$kv." ");
										 array_push($output , "Prefiks : -  ");
										 array_push($output , "Infiks : ".$inf1." ");
										 array_push($output , "Sufiks : -  ");
										 array_push($output , "Confiks : -  ");
										 array_push($output , "Ambifiks :  -  ");
										 array_push($output , "Kata dasar : ".$trim_inf1." ");
										 array_push($output , "Pola kanonik : -  ");
										  
										 }
									 }
			 
								 }
			 
							 // Cek infiks al-
							 if(preg_match("/$inf2/", substr($katajadi[$i], 0))){
								 $trim_inf2 = preg_replace("/^$inf2/", '', $katajadi[$i]);
								 $fonem_inf2 = preg_replace($konsonan, 'K', $trim_inf2);
								 $fonem_inf2kv = preg_replace($vokal, 'V', $fonem_inf2);
			 
								 // Cek pola kanonik
								 if(in_array($fonem_inf2kv, $rules)){
									 array_push($output , "Kata dasar: ".$katajadi[$i]." ");
									 array_push($output , "Pola fonem: ".$kv." ");
									 array_push($output , "Prefiks : -  ");
									 array_push($output , "Infiks : ".$inf2." ");
									 array_push($output , "Sufiks : -  ");
									 array_push($output , "Confiks : -  ");
									 array_push($output , "Ambifiks :  -  ");
									 array_push($output , "Kata dasar : ".$trim_inf2." ");
									 array_push($output , "Pola kanonik : ".$fonem_inf2kv." ");
									  
									 }
								 else{
									 array_push($output , "Kata dasar: ".$katajadi[$i]." ");
									 array_push($output , "Pola fonem: ".$kv." ");
									 array_push($output , "Prefiks : -  ");
									 array_push($output , "Infiks : ".$inf2." ");
									 array_push($output , "Sufiks : -  ");
									 array_push($output , "Confiks : -  ");
									 array_push($output , "Ambifiks :  -  ");
									 array_push($output , "Kata dasar : ".$trim_inf2." ");
									 array_push($output , "Pola kanonik : -  ");
									  
									 }
								 }
			 
							 // Cek infiks in-
							 if(preg_match("/$inf4/", substr($katajadi[$i], 0))){
								 $trim_inf4 = preg_replace("/^$inf4/", '', $katajadi[$i]);
								 $fonem_inf4 = preg_replace($konsonan, 'K', $trim_inf4);
								 $fonem_inf4kv = preg_replace($vokal, 'V', $fonem_inf4);
								 // Cek pola kanonik
								 if(in_array($fonem_inf4kv, $rules)){
									 array_push($output , "Kata dasar: ".$katajadi[$i]." ");
									 array_push($output , "Pola fonem: ".$kv." ");
									 array_push($output , "Prefiks : -  ");
									 array_push($output , "Infiks : ".$inf4." ");
									 array_push($output , "Sufiks : -  ");
									 array_push($output , "Confiks : -  ");
									 array_push($output , "Ambifiks :  -  ");
									 array_push($output , "Kata dasar : ".$trim_inf4." ");
									 array_push($output , "Pola kanonik : ".$fonem_inf4kv." ");
									  
									 }
								 else{
									 array_push($output , "Kata dasar: ".$katajadi[$i]." ");
									 array_push($output , "Pola fonem: ".$kv." ");
									 array_push($output , "Prefiks : -  ");
									 array_push($output , "Infiks : ".$inf4." ");
									 array_push($output , "Sufiks : -  ");
									 array_push($output , "Confiks : -  ");
									 array_push($output , "Ambifiks :  -  ");
									 array_push($output , "Kata dasar : ".$trim_inf4." ");
									 array_push($output , "Pola kanonik : -  ");
									  
									 }
								 }
			 
							 // Cek sufiks -eun
							 if(preg_match("/$suf2$/", substr($katajadi[$i], -3))){
								 $trim_suf2 = preg_replace("/$suf2$/", '', $katajadi[$i]);
								 $fonem_suf2 = preg_replace($konsonan, 'K', $trim_suf2);
								 $fonem_suf2kv = preg_replace($vokal, 'V', $fonem_suf2);
								 
								 // Cek sufiks -keun
								 if(preg_match("/$suf3$/", substr($katajadi[$i], -4))){
									 $trim_suf3 = preg_replace("/$suf3$/", '', $katajadi[$i]);
									 $fonem_suf3 = preg_replace($konsonan, 'K', $trim_suf3);
									 $fonem_suf3kv = preg_replace($vokal, 'V', $fonem_suf3);
									 // Cek pola kanonik
									 if(in_array($fonem_suf3kv, $rules)){
										 array_push($output , "Kata dasar: ".$katajadi[$i]." ");
										 array_push($output , "Pola fonem: ".$kv." ");
										 array_push($output , "Prefiks : -  ");
										 array_push($output , "Infiks : -  ");
										 array_push($output , "Sufiks : ".$suf3." ");
										 array_push($output , "Confiks : -  ");
										 array_push($output , "Ambifiks :  -  ");
										 array_push($output , "Kata dasar : ".$trim_suf3." ");
										 array_push($output , "Pola kanonik : ".$fonem_suf3kv." ");
										  
										 }
									 else{
										 array_push($output , "Kata dasar: ".$katajadi[$i]." ");
										 array_push($output , "Pola fonem: ".$kv." ");
										 array_push($output , "Prefiks : -  ");
										 array_push($output , "Infiks : -  ");
										 array_push($output , "Sufiks : ".$suf3." ");
										 array_push($output , "Confiks : -  ");
										 array_push($output , "Ambifiks :  -  ");
										 array_push($output , "Kata dasar : ".$trim_suf3." ");
										 array_push($output , "Pola kanonik : -  ");
										  
										 }
									 }
								 else{
									 // Cek pola kanonik
									 if(in_array($fonem_suf2kv, $rules)){
										 array_push($output , "Kata dasar: ".$katajadi[$i]." ");
										 array_push($output , "Pola fonem: ".$kv." ");
										 array_push($output , "Prefiks : -  ");
										 array_push($output , "Infiks : -  ");
										 array_push($output , "Sufiks : ".$suf2." ");
										 array_push($output , "Confiks : -  ");
										 array_push($output , "Ambifiks :  -  ");
										 array_push($output , "Kata dasar : ".$trim_suf2." ");
										 array_push($output , "Pola kanonik : ".$fonem_suf2kv." ");
										  
										 }
									 else{
										 array_push($output , "Kata dasar: ".$katajadi[$i]." ");
										 array_push($output , "Pola fonem: ".$kv." ");
										 array_push($output , "Prefiks : -  ");
										 array_push($output , "Infiks : -  ");
										 array_push($output , "Sufiks : ".$suf2." ");
										 array_push($output , "Confiks : -  ");
										 array_push($output , "Ambifiks :  -  ");
										 array_push($output , "Kata dasar : ".$trim_suf2." ");
										 array_push($output , "Pola kanonik : -  ");
										  
										 }
									 }
								 }
			 
							 // Cek sufiks -na
							 if(preg_match("/$suf4$/", substr($katajadi[$i], -2))){
								 $trim_suf4 = preg_replace("/$suf4$/", '', $katajadi[$i]);
								 $fonem_suf4 = preg_replace($konsonan, 'K', $trim_suf4);
								 $fonem_suf4kv = preg_replace($vokal, 'V', $fonem_suf4);
								 // Cek pola kanonik
								 if(in_array($fonem_suf4kv, $rules)){
									 array_push($output , "Kata dasar: ".$katajadi[$i]." ");
									 array_push($output , "Pola fonem: ".$kv." ");
									 array_push($output , "Prefiks : -  ");
									 array_push($output , "Infiks : -  ");
									 array_push($output , "Sufiks : ".$suf4." ");
									 array_push($output , "Confiks : -  ");
									 array_push($output , "Ambifiks :  -  ");
									 array_push($output , "Kata dasar : ".$trim_suf4." ");
									 array_push($output , "Pola kanonik : ".$fonem_suf4kv." ");
									  
									 }
								 else{
									 array_push($output , "Kata dasar: ".$katajadi[$i]." ");
									 array_push($output , "Pola fonem: ".$kv." ");
									 array_push($output , "Prefiks : -  ");
									 array_push($output , "Infiks : -  ");
									 array_push($output , "Sufiks : ".$suf4." ");
									 array_push($output , "Confiks : -  ");
									 array_push($output , "Ambifiks :  -  ");
									 array_push($output , "Kata dasar : ".$trim_suf4." ");
									 array_push($output , "Pola kanonik : -  ");
									  
									 }
								 }
			 
							 // Cek sufiks -ing
							 if(preg_match("/$suf5$/", substr($katajadi[$i], -3))){
								 $trim_suf5 = preg_replace("/$suf1$/", '', $katajadi[$i]);
								 $fonem_suf5 = preg_replace($konsonan, 'K', $trim_suf5);
								 $fonem_suf5kv = preg_replace($vokal, 'V', $fonem_suf5);
								 
								 // Cek sufiks -ning
								 if(preg_match("/$suf6$/", substr($katajadi[$i], -4))){
									 $trim_suf6 = preg_replace("/$suf1$/", '', $katajadi[$i]);
									 $fonem_suf6 = preg_replace($konsonan, 'K', $trim_suf6);
									 $fonem_suf6kv = preg_replace($vokal, 'V', $fonem_suf6);
			 
									 // Cek pola kanonik
									 if(in_array($fonem_suf6kv, $rules)){
										 array_push($output , "Kata dasar: ".$katajadi[$i]." ");
										 array_push($output , "Pola fonem: ".$kv." ");
										 array_push($output , "Prefiks : -  ");
										 array_push($output , "Infiks : -  ");
										 array_push($output , "Sufiks : ".$suf6." ");
										 array_push($output , "Confiks : -  ");
										 array_push($output , "Ambifiks :  -  ");
										 array_push($output , "Kata dasar : ".$trim_suf6." ");
										 array_push($output , "Pola kanonik : ".$fonem_suf6kv." ");
										  
										 }
									 else{
										 array_push($output , "Kata dasar: ".$katajadi[$i]." ");
										 array_push($output , "Pola fonem: ".$kv." ");
										 array_push($output , "Prefiks : -  ");
										 array_push($output , "Infiks : -  ");
										 array_push($output , "Sufiks : ".$suf6." ");
										 array_push($output , "Confiks : -  ");
										 array_push($output , "Ambifiks :  -  ");
										 array_push($output , "Kata dasar : ".$trim_suf6." ");
										 array_push($output , "Pola kanonik : -  ");
										  
										 }
									 }
								 else{
								 // Cek pola kanonik
									 if(in_array($fonem_suf5kv, $rules)){
										 array_push($output , "Kata dasar: ".$katajadi[$i]." ");
										 array_push($output , "Pola fonem: ".$kv." ");
										 array_push($output , "Prefiks : -  ");
										 array_push($output , "Infiks : -  ");
										 array_push($output , "Sufiks : ".$suf5." ");
										 array_push($output , "Confiks : -  ");
										 array_push($output , "Ambifiks :  -  ");
										 array_push($output , "Kata dasar : ".$trim_suf5." ");
										 array_push($output , "Pola kanonik : ".$fonem_suf5kv." ");
										  
										 }
									 else{
										 array_push($output , "Kata dasar: ".$katajadi[$i]." ");
										 array_push($output , "Pola fonem: ".$kv." ");
										 array_push($output , "Prefiks : -  ");
										 array_push($output , "Infiks : -  ");
										 array_push($output , "Sufiks : ".$suf5." ");
										 array_push($output , "Confiks : -  ");
										 array_push($output , "Ambifiks :  -  ");
										 array_push($output , "Kata dasar : ".$trim_suf5." ");
										 array_push($output , "Pola kanonik : -  ");
										  
										 }
									 }
								 } 
			 
							 }
						 }
					 }
					 return $output;
	}

	public static function has_dupes($array) {
		
		// dd(count($array));
		$jumlah_kosong = 0 ;
		foreach ($array as $key => $value) {
			if ($value == ' -  '){
				$jumlah_kosong = $jumlah_kosong +1;
			}
		}
		// dd(count($array)-$jumlah_kosong);
		return count($array)-$jumlah_kosong;
	}

}
