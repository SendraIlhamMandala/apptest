<!-- <form action="" method='post'>
    <input type='text' name='stem'>
    <button type='submit' name='submit'>Submit</button>
</form> -->

<?php

// kanonik rule
$rules = array('V','VK','KV','KVK','KKV','KKVK','VV','VVK','VKV','VKVK','VKKV','VKKVK','VKVK','VKKV','VKKKV','VKKKVK','KVV','KVVK','KVKV','KVKVK','KVKKV','KVKKVK','KVKKV','KVKKVK','KVKKKV','KVKKKVK','VVKV','VKVKV','VKVKVK','VKKVKV','VKKVVK','VKKVKVK','VKKVKKVK','VKKVKKV','KVKVVK','KVKVKV','KVKVV','KVKVKVK','KVKVKKVK','KVVKVK','KVKVKKKVK','KVKKVKVK','KVKKVKV','KVKVKKV','KVKKVKVK','KVKKVVK','KVKKVKKVK','KVKKVKKKVK','KKVKVKVK','VKVKVKV','VKVKVVK','VKVKKVVK','VKVKVKVK','KVKVKKKVKVKKVKVKVK','VKKVKVKV','KVKVKVKV','KVKVKVKVK','KVKVKVKV','KVKVKVKKVK','KVKVVKKVK','KVKVVKV','KVKKVKVKVK','KVKVKKVKV','KVKVVKVK','KVKKVKVKV','KVKKVKVV','KVKKVVKVK','KVKVKKVKVK','KVKVKKVVK','VKVKVKVKVK','KVKVKVKVKV','KVKVKVKVKKV','KVKVKVKVKKVK','KVKKVKVKVKVK','KVKVKVKVKKVK','KVKVKVKVVK','VKK','KVKK','KKKV','KKVKK','KKKVK','KVKKK');

// baca input
if(isset($_POST['submit']))
  	{
  		$kecap = $_POST['stem'];
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
							echo "Kata dasar: ".$katajadi[$i]."<br>";
							echo "Pola fonem: ".$kv."<br>";
							echo "Prefiks : n <br>";
							echo "Infiks : - <br>";
							echo "Sufiks : - <br>";
							echo "Confiks : - <br>";
							echo "Ambifiks :  - <br>";
							echo "Kata dasar : ".$trim_t."<br>";
							echo "Pola kanonik : ".$fonem_tkv."<br>";
							echo "<hr>";
							}
						else{
							echo "Kata dasar: ".$katajadi[$i]."<br>";
							echo "Pola fonem: ".$kv."<br>";
							echo "Prefiks : t <br>";
							echo "Infiks : - <br>";
							echo "Sufiks : - <br>";
							echo "Confiks : - <br>";
							echo "Ambifiks :  - <br>";
							echo "Kata dasar : ".$trim_t."<br>";
							echo "Pola kanonik : - <br>";
							echo "<hr>";
						}
					}

				// ny mengganti c, j, s
				if(preg_match("/^ny/", substr($katajadi[$i], 0))){
					$trim_t = preg_replace("/^ny/", 'c|j|s', $katajadi[$i]);
					$fonem_t = preg_replace($konsonan, 'K', $trim_t);
					$fonem_tkv = preg_replace($vokal, 'V', $fonem_t);
					// Cek pola kanonik
						if(in_array($fonem_tkv, $rules)){
							echo "Kata dasar: ".$katajadi[$i]."<br>";
							echo "Pola fonem: ".$kv."<br>";
							echo "Prefiks : c|j|s<br>";
							echo "Infiks : - <br>";
							echo "Sufiks : - <br>";
							echo "Confiks : - <br>";
							echo "Ambifiks :  - <br>";
							echo "Kata dasar : ".$trim_t."<br>";
							echo "Pola kanonik : ".$fonem_tkv."<br>";
							echo "<hr>";
							}
						else{
							echo "Kata dasar: ".$katajadi[$i]."<br>";
							echo "Pola fonem: ".$kv."<br>";
							echo "Prefiks : c|j|s<br>";
							echo "Infiks : - <br>";
							echo "Sufiks : - <br>";
							echo "Confiks : - <br>";
							echo "Ambifiks :  - <br>";
							echo "Kata dasar : ".$trim_t."<br>";
							echo "Pola kanonik : - <br>";
							echo "<hr>";
						}
					}

				// m mengganti p,b
				if(preg_match("/^m/", substr($katajadi[$i], 0))){
					$trim_t = preg_replace("/^m/", 'p|b', $katajadi[$i]);
					$fonem_t = preg_replace($konsonan, 'K', $trim_t);
					$fonem_tkv = preg_replace($vokal, 'V', $fonem_t);
					// Cek pola kanonik
						if(in_array($fonem_tkv, $rules)){
							echo "Kata dasar: ".$katajadi[$i]."<br>";
							echo "Pola fonem: ".$kv."<br>";
							echo "Prefiks : p|b<br>";
							echo "Infiks : - <br>";
							echo "Sufiks : - <br>";
							echo "Confiks : - <br>";
							echo "Ambifiks :  - <br>";
							echo "Kata dasar : ".$trim_t."<br>";
							echo "Pola kanonik : ".$fonem_tkv."<br>";
							echo "<hr>";
							}
						else{
							echo "Kata dasar: ".$katajadi[$i]."<br>";
							echo "Pola fonem: ".$kv."<br>";
							echo "Prefiks : t<br>";
							echo "Infiks : - <br>";
							echo "Sufiks : - <br>";
							echo "Confiks : - <br>";
							echo "Ambifiks :  - <br>";
							echo "Kata dasar : ".$trim_t."<br>";
							echo "Pola kanonik : - <br>";
							echo "<hr>";
						}
					}

				// ng mengganti k
				if(preg_match("/^ng/", substr($katajadi[$i], 0))){
					$trim_t = preg_replace("/^ng/", 'k', $katajadi[$i]);
					$fonem_t = preg_replace($konsonan, 'K', $trim_t);
					$fonem_tkv = preg_replace($vokal, 'V', $fonem_t);
					// Cek pola kanonik
						if(in_array($fonem_tkv, $rules)){
							echo "Kata dasar: ".$katajadi[$i]."<br>";
							echo "Pola fonem: ".$kv."<br>";
							echo "Prefiks : ng<br>";
							echo "Infiks : - <br>";
							echo "Sufiks : - <br>";
							echo "Confiks : - <br>";
							echo "Ambifiks :  - <br>";
							echo "Kata dasar : ".$trim_t."<br>";
							echo "Pola kanonik : ".$fonem_tkv."<br>";
							echo "<hr>";
							}
						else{
							echo "Kata dasar: ".$katajadi[$i]."<br>";
							echo "Pola fonem: ".$kv."<br>";
							echo "Prefiks : ng<br>";
							echo "Infiks : - <br>";
							echo "Sufiks : - <br>";
							echo "Confiks : - <br>";
							echo "Ambifiks :  - <br>";
							echo "Kata dasar : ".$trim_t."<br>";
							echo "Pola kanonik : - <br>";
							echo "<hr>";
						}
					}

				// ng didepan huruf vokal
				if(preg_match("/^ng[aiueo]/", substr($katajadi[$i], 0))){
					$trim_t = preg_replace("/^ng/", '', $katajadi[$i]);
					$fonem_t = preg_replace($konsonan, 'K', $trim_t);
					$fonem_tkv = preg_replace($vokal, 'V', $fonem_t);
					// Cek pola kanonik
						if(in_array($fonem_tkv, $rules)){
							echo "Kata dasar: ".$katajadi[$i]."<br>";
							echo "Pola fonem: ".$kv."<br>";
							echo "Prefiks : ng<br>";
							echo "Infiks : - <br>";
							echo "Sufiks : - <br>";
							echo "Confiks : - <br>";
							echo "Ambifiks :  - <br>";
							echo "Kata dasar : ".$trim_t."<br>";
							echo "Pola kanonik : ".$fonem_tkv."<br>";
							echo "<hr>";
							}
						else{
							echo "Kata dasar: ".$katajadi[$i]."<br>";
							echo "Pola fonem: ".$kv."<br>";
							echo "Prefiks : ng<br>";
							echo "Infiks : - <br>";
							echo "Sufiks : - <br>";
							echo "Confiks : - <br>";
							echo "Ambifiks :  - <br>";
							echo "Kata dasar : ".$trim_t."<br>";
							echo "Pola kanonik : - <br>";
							echo "<hr>";
						}
					}

				// ng berubah nga didepan huruf d
				if(preg_match("/^ng/", substr($katajadi[$i], 0))){
					$trim_t = preg_replace("/^ng/", 'd', $katajadi[$i]);
					$fonem_t = preg_replace($konsonan, 'K', $trim_t);
					$fonem_tkv = preg_replace($vokal, 'V', $fonem_t);
					// Cek pola kanonik
						if(in_array($fonem_tkv, $rules)){
							echo "Kata dasar: ".$katajadi[$i]."<br>";
							echo "Pola fonem: ".$kv."<br>";
							echo "Prefiks : ng<br>";
							echo "Infiks : - <br>";
							echo "Sufiks : - <br>";
							echo "Confiks : - <br>";
							echo "Ambifiks :  - <br>";
							echo "Kata dasar : ".$trim_t."<br>";
							echo "Pola kanonik : ".$fonem_tkv."<br>";
							echo "<hr>";
							}
						else{
							echo "Kata dasar: ".$katajadi[$i]."<br>";
							echo "Pola fonem: ".$kv."<br>";
							echo "Prefiks : t<br>";
							echo "Infiks : - <br>";
							echo "Sufiks : - <br>";
							echo "Confiks : - <br>";
							echo "Ambifiks :  - <br>";
							echo "Kata dasar : ".$trim_t."<br>";
							echo "Pola kanonik : - <br>";
							echo "<hr>";
						}
					}

				// ng berubah nge didepan huruf c
				if(preg_match("/^nge/", substr($katajadi[$i], 0))){
					$trim_t = preg_replace("/^nge/", 'c', $katajadi[$i]);
					$fonem_t = preg_replace($konsonan, 'K', $trim_t);
					$fonem_tkv = preg_replace($vokal, 'V', $fonem_t);
					// Cek pola kanonik
						if(in_array($fonem_tkv, $rules)){
							echo "Kata dasar: ".$katajadi[$i]."<br>";
							echo "Pola fonem: ".$kv."<br>";
							echo "Prefiks : nge<br>";
							echo "Infiks : - <br>";
							echo "Sufiks : - <br>";
							echo "Confiks : - <br>";
							echo "Ambifiks :  - <br>";
							echo "Kata dasar : ".$trim_t."<br>";
							echo "Pola kanonik : ".$fonem_tkv."<br>";
							echo "<hr>";
							}
						else{
							echo "Kata dasar: ".$katajadi[$i]."<br>";
							echo "Pola fonem: ".$kv."<br>";
							echo "Prefiks : t<br>";
							echo "Infiks : - <br>";
							echo "Sufiks : - <br>";
							echo "Confiks : - <br>";
							echo "Ambifiks :  - <br>";
							echo "Kata dasar : ".$trim_t."<br>";
							echo "Pola kanonik : - <br>";
							echo "<hr>";
						}
					}

				
				if(in_array($kv, $rules)){
					echo "Kata dasar: ".$katajadi[$i]."<br>";
					echo "Pola fonem: ".$kv."<br>";
					echo "Prefiks : - <br>";
					echo "Infiks : - <br>";
					echo "Sufiks : - <br>";
					echo "Confiks : - <br>";
					echo "Ambifiks :  - <br>";
					echo "Kata dasar : ".$trim."<br>";
					echo "Pola kanonik : ".$fonem_kv."<br>";
					echo "<hr>";
					}
				else{
					echo "Kata dasar: ".$katajadi[$i]."<br>";
					echo "Pola fonem: ".$kv."<br>";
					echo "Prefiks : - <br>";
					echo "Infiks : - <br>";
					echo "Sufiks : - <br>";
					echo "Confiks : - <br>";
					echo "Ambifiks :  - <br>";
					echo "Kata dasar : ".$trim."<br>";
					echo "Pola kanonik : - <br>";
					echo "<hr>";
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
							echo "Kata dasar: ".$katajadi[$i]."<br>";
							echo "Pola fonem: ".$kv."<br>";
							echo "Prefiks : c|j|s<br>";
							echo "Infiks : - <br>";
							echo "Sufiks : - <br>";
							echo "Confiks : - <br>";
							echo "Ambifiks :  - <br>";
							echo "Kata dasar : ".$trim_t."<br>";
							echo "Pola kanonik : ".$fonem_tkv."<br>";
							echo "<hr>";
							}
						else{
							echo "Kata dasar: ".$katajadi[$i]."<br>";
							echo "Pola fonem: ".$kv."<br>";
							echo "Prefiks : c|j|s<br>";
							echo "Infiks : - <br>";
							echo "Sufiks : - <br>";
							echo "Confiks : - <br>";
							echo "Ambifiks :  - <br>";
							echo "Kata dasar : ".$trim_t."<br>";
							echo "Pola kanonik : - <br>";
							echo "<hr>";
						}
					}

				// m mengganti p,b
				if(preg_match("/^m/", substr($katajadi[$i], 0))){
					$trim_t = preg_replace("/^m/", 'p|b', $katajadi[$i]);
					$fonem_t = preg_replace($konsonan, 'K', $trim_t);
					$fonem_tkv = preg_replace($vokal, 'V', $fonem_t);
					// Cek pola kanonik
						if(in_array($fonem_tkv, $rules)){
							echo "Kata dasar: ".$katajadi[$i]."<br>";
							echo "Pola fonem: ".$kv."<br>";
							echo "Prefiks : p|b<br>";
							echo "Infiks : - <br>";
							echo "Sufiks : - <br>";
							echo "Confiks : - <br>";
							echo "Ambifiks :  - <br>";
							echo "Kata dasar : ".$trim_t."<br>";
							echo "Pola kanonik : ".$fonem_tkv."<br>";
							echo "<hr>";
							}
						else{
							echo "Kata dasar: ".$katajadi[$i]."<br>";
							echo "Pola fonem: ".$kv."<br>";
							echo "Prefiks : t<br>";
							echo "Infiks : - <br>";
							echo "Sufiks : - <br>";
							echo "Confiks : - <br>";
							echo "Ambifiks :  - <br>";
							echo "Kata dasar : ".$trim_t."<br>";
							echo "Pola kanonik : - <br>";
							echo "<hr>";
						}
					}

				// ng mengganti k
				if(preg_match("/^ng/", substr($katajadi[$i], 0))){
					$trim_t = preg_replace("/^ng/", 'k', $katajadi[$i]);
					$fonem_t = preg_replace($konsonan, 'K', $trim_t);
					$fonem_tkv = preg_replace($vokal, 'V', $fonem_t);
					// Cek pola kanonik
						if(in_array($fonem_tkv, $rules)){
							echo "Kata dasar: ".$katajadi[$i]."<br>";
							echo "Pola fonem: ".$kv."<br>";
							echo "Prefiks : ng<br>";
							echo "Infiks : - <br>";
							echo "Sufiks : - <br>";
							echo "Confiks : - <br>";
							echo "Ambifiks :  - <br>";
							echo "Kata dasar : ".$trim_t."<br>";
							echo "Pola kanonik : ".$fonem_tkv."<br>";
							echo "<hr>";
							}
						else{
							echo "Kata dasar: ".$katajadi[$i]."<br>";
							echo "Pola fonem: ".$kv."<br>";
							echo "Prefiks : ng<br>";
							echo "Infiks : - <br>";
							echo "Sufiks : - <br>";
							echo "Confiks : - <br>";
							echo "Ambifiks :  - <br>";
							echo "Kata dasar : ".$trim_t."<br>";
							echo "Pola kanonik : - <br>";
							echo "<hr>";
						}
					}

				// ng didepan huruf vokal
				if(preg_match("/^nga|^ngi|^ngu|^nge|^ngo/", substr($katajadi[$i], 0))){
					$trim_t = preg_replace("/^nga|^ngi|^ngu|^nge|^ngo/", '', $katajadi[$i]);
					$fonem_t = preg_replace($konsonan, 'K', $trim_t);
					$fonem_tkv = preg_replace($vokal, 'V', $fonem_t);
					// Cek pola kanonik
						if(in_array($fonem_tkv, $rules)){
							echo "Kata dasar: ".$katajadi[$i]."<br>";
							echo "Pola fonem: ".$kv."<br>";
							echo "Prefiks : ng<br>";
							echo "Infiks : - <br>";
							echo "Sufiks : - <br>";
							echo "Confiks : - <br>";
							echo "Ambifiks :  - <br>";
							echo "Kata dasar : ".$trim_t."<br>";
							echo "Pola kanonik : ".$fonem_tkv."<br>";
							echo "<hr>";
							}
						else{
							echo "Kata dasar: ".$katajadi[$i]."<br>";
							echo "Pola fonem: ".$kv."<br>";
							echo "Prefiks : ng<br>";
							echo "Infiks : - <br>";
							echo "Sufiks : - <br>";
							echo "Confiks : - <br>";
							echo "Ambifiks :  - <br>";
							echo "Kata dasar : ".$trim_t."<br>";
							echo "Pola kanonik : - <br>";
							echo "<hr>";
						}
					}

				// ng berubah nga didepan huruf d
				if(preg_match("/^ng/", substr($katajadi[$i], 0))){
					$trim_t = preg_replace("/^ng/", 'd', $katajadi[$i]);
					$fonem_t = preg_replace($konsonan, 'K', $trim_t);
					$fonem_tkv = preg_replace($vokal, 'V', $fonem_t);
					// Cek pola kanonik
						if(in_array($fonem_tkv, $rules)){
							echo "Kata dasar: ".$katajadi[$i]."<br>";
							echo "Pola fonem: ".$kv."<br>";
							echo "Prefiks : ng<br>";
							echo "Infiks : - <br>";
							echo "Sufiks : - <br>";
							echo "Confiks : - <br>";
							echo "Ambifiks :  - <br>";
							echo "Kata dasar : ".$trim_t."<br>";
							echo "Pola kanonik : ".$fonem_tkv."<br>";
							echo "<hr>";
							}
						else{
							echo "Kata dasar: ".$katajadi[$i]."<br>";
							echo "Pola fonem: ".$kv."<br>";
							echo "Prefiks : t<br>";
							echo "Infiks : - <br>";
							echo "Sufiks : - <br>";
							echo "Confiks : - <br>";
							echo "Ambifiks :  - <br>";
							echo "Kata dasar : ".$trim_t."<br>";
							echo "Pola kanonik : - <br>";
							echo "<hr>";
						}
					}

				// ng berubah nge didepan huruf c
				if(preg_match("/^nge/", substr($katajadi[$i], 0))){
					$trim_t = preg_replace("/^nge/", 'c', $katajadi[$i]);
					$fonem_t = preg_replace($konsonan, 'K', $trim_t);
					$fonem_tkv = preg_replace($vokal, 'V', $fonem_t);
					// Cek pola kanonik
						if(in_array($fonem_tkv, $rules)){
							echo "Kata dasar: ".$katajadi[$i]."<br>";
							echo "Pola fonem: ".$kv."<br>";
							echo "Prefiks : nge<br>";
							echo "Infiks : - <br>";
							echo "Sufiks : - <br>";
							echo "Confiks : - <br>";
							echo "Ambifiks :  - <br>";
							echo "Kata dasar : ".$trim_t."<br>";
							echo "Pola kanonik : ".$fonem_tkv."<br>";
							echo "<hr>";
							}
						else{
							echo "Kata dasar: ".$katajadi[$i]."<br>";
							echo "Pola fonem: ".$kv."<br>";
							echo "Prefiks : t<br>";
							echo "Infiks : - <br>";
							echo "Sufiks : - <br>";
							echo "Confiks : - <br>";
							echo "Ambifiks :  - <br>";
							echo "Kata dasar : ".$trim_t."<br>";
							echo "Pola kanonik : - <br>";
							echo "<hr>";
						}
					}

				// ba-
				if(preg_match("/^$pref1/", substr($katajadi[$i], 0))){
					$trim_pref1 = preg_replace("/^$pref1/", '', $katajadi[$i]);
					$fonem_pref1 = preg_replace($konsonan, 'K', $trim_pref1);
					$fonem_pref1kv = preg_replace($vokal, 'V', $fonem_pref1);

					// Cek pola kanonik
					if(in_array($fonem_pref1kv, $rules)){
						echo "Kata dasar: ".$katajadi[$i]."<br>";
						echo "Pola fonem: ".$kv."<br>";
						echo "Prefiks : ".$pref1."<br>";
						echo "Infiks : - <br>";
						echo "Sufiks : - <br>";
						echo "Confiks : - <br>";
						echo "Ambifiks :  - <br>";
						echo "Kata dasar : ".$trim_pref1."<br>";
						echo "Pola kanonik : ".$fonem_pref1kv."<br>";
						echo "<hr>";
						}
					else{
						echo "Kata dasar: ".$katajadi[$i]."<br>";
						echo "Pola fonem: ".$kv."<br>";
						echo "Prefiks : ".$pref1."<br>";
						echo "Infiks : - <br>";
						echo "Sufiks : - <br>";
						echo "Confiks : - <br>";
						echo "Ambifiks :  - <br>";
						echo "Kata dasar : ".$trim_pref1."<br>";
						echo "Pola kanonik : - <br>";
						echo "<hr>";
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
							echo "Kata dasar: ".$trim_pref7."<br>";
							echo "Pola fonem: ".$fonem_pref7kv."<br>";
							echo "Prefiks : ".$pref7."<br>";
							echo "Infiks : - <br>";
							echo "Sufiks : ".$suf1."<br>";
							echo "Confiks : ".$pref7."+ - +".$suf1."<br>";
							echo "Ambifiks :  - <br>";
							echo "Kata dasar : ".$trim_suf1."<br>";
							echo "Pola kanonik : ".$fonem_suf1kv."<br>";
							echo "<hr>";
							}
						else{
							echo "Kata dasar: ".$trim_pref7."<br>";
							echo "Pola fonem: ".$fonem_pref7kv."<br>";
							echo "Prefiks : ".$pref7."<br>";
							echo "Infiks : - <br>";
							echo "Sufiks : ".$suf1."<br>";
							echo "Confiks : ".$pref7."+ - +".$suf1."<br>";
							echo "Ambifiks :  - <br>";
							echo "Kata dasar : ".$trim_suf1."<br>";
							echo "Pola kanonik : - <br>";
							echo "<hr>";
							}
						}

					else{
						// Cek pola kanonik
						if(in_array($fonem_pref7kv, $rules)){
							echo "Kata dasar: ".$katajadi[$i]."<br>";
							echo "Pola fonem: ".$kv."<br>";
							echo "Prefiks : ".$pref7."<br>";
							echo "Infiks : - <br>";
							echo "Sufiks : - <br>";
							echo "Confiks : - <br>";
							echo "Ambifiks :  - <br>";
							echo "Kata dasar : ".$trim_pref7."<br>";
							echo "Pola kanonik : ".$fonem_pref7kv."<br>";
							echo "<hr>";
							}
						else{
							echo "Kata dasar: ".$katajadi[$i]."<br>";
							echo "Pola fonem: ".$kv."<br>";
							echo "Prefiks : ".$pref7."<br>";
							echo "Infiks : - <br>";
							echo "Sufiks : - <br>";
							echo "Confiks : - <br>";
							echo "Ambifiks :  - <br>";
							echo "Kata dasar : ".$trim_pref7."<br>";
							echo "Pola kanonik : - <br>";
							echo "<hr>";
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
						echo "Kata dasar: ".$katajadi[$i]."<br>";
						echo "Pola fonem: ".$kv."<br>";
						echo "Prefiks : - <br>";
						echo "Infiks : - <br>";
						echo "Sufiks : - <br>";
						echo "Confiks : - <br>";
						echo "Ambifiks :  - <br>";
						echo "Kata dasar : ".$trim_pref11."<br>";
						echo "Pola kanonik : ".$fonem_pref11kv."<br>";
						echo "<hr>";
						}
					else{
						echo "Kata dasar: ".$katajadi[$i]."<br>";
						echo "Pola fonem: ".$kv."<br>";
						echo "Prefiks : ".$pref11."<br>";
						echo "Infiks : - <br>";
						echo "Sufiks : - <br>";
						echo "Confiks : - <br>";
						echo "Ambifiks :  - <br>";
						echo "Kata dasar : ".$trim_pref11."<br>";
						echo "Pola kanonik : - <br>";
						echo "<hr>";
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
								echo "Kata dasar: ".$katajadi[$i]."<br>";
								echo "Pola fonem: ".$kv."<br>";
								echo "Prefiks1 : ".$pref2."<br>";
								echo "Prefiks2 : ".$pref8."<br>";
								echo "Prefiks3 : ".$pref3."<br>";
								echo "Infiks : - <br>";
								echo "Sufiks : - <br>";
								echo "Confiks : - <br>";
								echo "Ambifiks :  - <br>";
								echo "Kata dasar : ".$trim_pref3."<br>";
								echo "Pola kanonik : ".$fonem_pref3kv."<br>";
								echo "<hr>";
								}
							else{
								echo "Kata dasar: ".$katajadi[$i]."<br>";
								echo "Pola fonem: ".$kv."<br>";
								echo "Prefiks1 : ".$pref2."<br>";
								echo "Prefiks2 : ".$pref8."<br>";
								echo "Infiks : - <br>";
								echo "Sufiks : - <br>";
								echo "Confiks : - <br>";
								echo "Ambifiks :  - <br>";
								echo "Kata dasar : ".$trim_pref8."<br>";							
								echo "Pola kanonik : - <br>";
								echo "<hr>";
								}
							}
						
						else{
							// Cek pola kanonik
							if(in_array($fonem_pref8kv, $rules)){
								echo "Kata dasar: ".$katajadi[$i]."<br>";
								echo "Pola fonem: ".$kv."<br>";
								echo "Prefiks1 : ".$pref2."<br>";
								echo "Prefiks2 : ".$pref8."<br>";
								echo "Infiks : - <br>";
								echo "Sufiks : - <br>";
								echo "Confiks : - <br>";
								echo "Ambifiks :  - <br>";
								echo "Kata dasar : ".$trim_pref8."<br>";
								echo "Pola kanonik : ".$fonem_pref8kv."<br>";
								echo "<hr>";
								}
							else{
								echo "Kata dasar: ".$katajadi[$i]."<br>";
								echo "Pola fonem: ".$kv."<br>";
								echo "Prefiks1 : ".$pref2."<br>";
								echo "Prefiks2 : ".$pref8."<br>";
								echo "Infiks : - <br>";
								echo "Sufiks : - <br>";
								echo "Confiks : - <br>";
								echo "Ambifiks :  - <br>";
								echo "Kata dasar : ".$trim_pref8."<br>";							
								echo "Pola kanonik : - <br>";
								echo "<hr>";
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
							echo "Kata dasar: ".$katajadi[$i]."<br>";
							echo "Pola fonem: ".$kv."<br>";
							echo "Prefiks : ".$pref2."<br>";
							echo "Infiks : - <br>";
							echo "Sufiks : ".$suf1."<br>";
							echo "Confiks : ".$pref2."+ - +".$suf1."<br>";
							echo "Ambifiks :  - <br>";
							echo "Kata dasar : ".$trim_suf1."<br>";
							echo "Pola kanonik : ".$fonem_suf1kv."<br>";
							echo "<hr>";
							}
						else{
							echo "Kata dasar: ".$katajadi[$i]."<br>";
							echo "Pola fonem: ".$kv."<br>";
							echo "Prefiks : ".$pref2."<br>";
							echo "Infiks : - <br>";
							echo "Sufiks : ".$suf1."<br>";
							echo "Confiks : ".$pref2."+ - +".$suf1."<br>";
							echo "Ambifiks :  - <br>";
							echo "Kata dasar : ".$trim_suf1."<br>";							
							echo "Pola kanonik : - <br>";
							echo "<hr>";
							}

						}
					
					// keun
					elseif(preg_match("/$suf3$/", substr($trim_pref2, -4))){						
						$trim_suf3 = preg_replace("/$suf3$/", '', $trim_pref2);
						$fonem_suf3 = preg_replace($konsonan, 'K', $trim_suf3);
						$fonem_suf3kv = preg_replace($vokal, 'V', $fonem_suf3);

						// Cek pola kanonik
						if(in_array($fonem_suf3kv, $rules)){
							echo "Kata dasar: ".$katajadi[$i]."<br>";
							echo "Pola fonem: ".$kv."<br>";
							echo "Prefiks1 : ".$pref2."<br>";
							echo "Infiks : - <br>";
							echo "Sufiks : ".$suf3."<br>";
							echo "Confiks : ".$pref2."+ - +".$suf3."<br>";
							echo "Ambifiks :  - <br>";
							echo "Kata dasar : ".$trim_suf3."<br>";
							echo "Pola kanonik : ".$fonem_suf3kv."<br>";
							echo "<hr>";
							}
						else{
							echo "Kata dasar: ".$katajadi[$i]."<br>";
							echo "Pola fonem: ".$kv."<br>";
							echo "Prefiks : ".$pref2."<br>";
							echo "Infiks : - <br>";
							echo "Sufiks : ".$suf3."<br>";
							echo "Confiks : ".$pref2."+ - +".$suf3."<br>";
							echo "Ambifiks :  - <br>";
							echo "Kata dasar : ".$trim_suf3."<br>";							
							echo "Pola kanonik : - <br>";
							echo "<hr>";
							}
					}

					elseif(preg_match("/^$pref8/", substr($trim_pref2, 0))){

					}

					elseif(preg_match("/^$pref8/", substr($trim_pref2, 0))){

					}

					else{
						// Cek pola kanonik
						if(in_array($fonem_pref2kv, $rules)){
							echo "Kata dasar: ".$katajadi[$i]."<br>";
							echo "Pola fonem: ".$kv."<br>";
							echo "Prefiks : ".$pref2."<br>";
							echo "Infiks : - <br>";
							echo "Sufiks : - <br>";
							echo "Confiks : - <br>";
							echo "Ambifiks :  - <br>";
							echo "Kata dasar : ".$trim_pref2."<br>";
							echo "Pola kanonik : ".$fonem_pref2kv."<br>";
							echo "<hr>";
							}
						else{
							echo "Kata dasar: ".$katajadi[$i]."<br>";
							echo "Pola fonem: ".$kv."<br>";
							echo "Prefiks : ".$pref2."<br>";
							echo "Infiks : - <br>";
							echo "Sufiks : - <br>";
							echo "Confiks : - <br>";
							echo "Ambifiks :  - <br>";
							echo "Kata dasar : ".$trim_pref2."<br>";
							echo "Pola kanonik : - <br>";
							echo "<hr>";
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
							echo "Kata dasar: ".$katajadi[$i]."<br>";
							echo "Pola fonem: ".$kv."<br>";
							echo "Prefiks1 : ".$pref3."<br>";
							echo "Prefiks2 : ".$pref8."<br>";
							echo "Infiks : - <br>";
							echo "Sufiks : - <br>";
							echo "Confiks : ".$pref3.$pref8."<br>";
							echo "Ambifiks :  - <br>";
							echo "Kata dasar : ".$trim_pref81."<br>";
							echo "Pola kanonik : ".$fonem_pref81kv."<br>";
							echo "<hr>";
							}
						else{
							echo "Kata dasar: ".$katajadi[$i]."<br>";
							echo "Pola fonem: ".$kv."<br>";
							echo "Prefiks : ".$pref3."<br>";
							echo "Infiks : - <br>";
							echo "Sufiks : - <br>";
							echo "Confiks : - <br>";
							echo "Ambifiks :  - <br>";
							echo "Kata dasar : ".$trim_pref81."<br>";
							echo "Pola kanonik : - <br>";
							echo "<hr>";
							}
						}

					// -an
					elseif(preg_match("/$suf1$/", substr($trim_pref3, 0))){
						$trim_suf1 = preg_replace("/$suf1$/", '', $trim_pref3);
						$fonem_suf1 = preg_replace($konsonan, 'K', $trim_suf1);
						$fonem_suf1kv = preg_replace($vokal, 'V', $fonem_suf1);
						
						// Cek pola kanonik
						if(in_array($fonem_suf1kv, $rules)){
							echo "Kata dasar: ".$katajadi[$i]."<br>";
							echo "Pola fonem: ".$kv."<br>";
							echo "Prefiks : ".$pref3."<br>";
							echo "Infiks : - <br>";
							echo "Sufiks : ".$suf1."<br>";
							echo "Confiks : ".$pref3."+ - +".$suf1."<br>";
							echo "Ambifiks :  - <br>";
							echo "Kata dasar : ".$trim_suf1."<br>";
							echo "Pola kanonik : ".$fonem_suf1kv."<br>";
							echo "<hr>";
							}
						else{
							echo "Kata dasar: ".$katajadi[$i]."<br>";
							echo "Pola fonem: ".$kv."<br>";
							echo "Prefiks : ".$pref3."<br>";
							echo "Infiks : - <br>";
							echo "Sufiks : ".$suf1."<br>";
							echo "Confiks : ".$pref3."+ - +".$suf1."<br>";
							echo "Ambifiks :  - <br>";
							echo "Kata dasar : ".$trim_suf1."<br>";
							echo "Pola kanonik : - <br>";
							echo "<hr>";
							}
						}

					// -keun
					elseif(preg_match("/$suf3$/", substr($trim_pref3, -4))){
						$trim_suf3 = preg_replace("/$suf3$/", '', $trim_pref3);
						$fonem_suf3 = preg_replace($konsonan, 'K', $trim_suf3);
						$fonem_suf3kv = preg_replace($vokal, 'V', $fonem_suf3);
						
						// Cek pola kanonik
						if(in_array($fonem_suf3kv, $rules)){
							echo "Kata dasar: ".$katajadi[$i]."<br>";
							echo "Pola fonem: ".$kv."<br>";
							echo "Prefiks : ".$pref3."<br>";
							echo "Infiks : - <br>";
							echo "Sufiks : ".$suf3."<br>";
							echo "Confiks : - <br>";
							echo "Ambifiks :  ".$pref3."+ - +".$suf3."<br>";
							echo "Kata dasar : ".$trim_suf3."<br>";
							echo "Pola kanonik : ".$fonem_suf3kv."<br>";
							echo "<hr>";
							}
						else{
							echo "Kata dasar: ".$katajadi[$i]."<br>";
							echo "Pola fonem: ".$kv."<br>";
							echo "Prefiks : ".$pref3."<br>";
							echo "Infiks : - <br>";
							echo "Sufiks : ".$suf3."<br>";
							echo "Confiks : - <br>";
							echo "Ambifiks : ".$pref3."+ - +".$suf3."<br>";
							echo "Kata dasar : ".$trim_pref3."<br>";
							echo "Pola kanonik : - <br>";
							echo "<hr>";
							}
						}

					else{
						// Cek pola kanonik
						if(in_array($fonem_pref3kv, $rules)){
							echo "Kata dasar: ".$katajadi[$i]."<br>";
							echo "Pola fonem: ".$kv."<br>";
							echo "Prefiks : ".$pref3."<br>";
							echo "Infiks : - <br>";
							echo "Sufiks : - <br>";
							echo "Confiks : - <br>";
							echo "Ambifiks : - <br>";
							echo "Kata dasar : ".$trim_pref3."<br>";
							echo "Pola kanonik : ".$fonem_pref3kv."<br>";
							echo "<hr>";
							}
						else{
							echo "Kata dasar: ".$katajadi[$i]."<br>";
							echo "Pola fonem: ".$kv."<br>";
							echo "Prefiks : ".$pref3."<br>";
							echo "Infiks : - <br>";
							echo "Sufiks : - <br>";
							echo "Confiks : - <br>";
							echo "Ambifiks : - <br>";
							echo "Kata dasar : ".$trim_suf3."<br>";
							echo "Pola kanonik : - <br>";
							echo "<hr>";
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
							echo "Kata awal : ".$katajadi[$i].'<br>';
							echo "Pola fonem : ".$kv.'<br>';
							echo "Prefiks : ".$pref5."<br>";
							echo "Infiks : - <br>";
							echo "Sufiks :  ".$suf1."<br>";
							echo "Confiks :  ".$pref5."+ - +".$suf1."<br>";
							echo "Ambifiks : - <br>";
							echo "Kata dasar : ".$trim_pref54.'<br>';
							echo "Pola kanonik : ".$fonem_pref54kv."<br>";
							echo "<hr>";
							}
						else{	
							echo "Kata awal : ".$katajadi[$i].'<br>';
							echo "Pola fonem : ".$kv.'<br>';
							echo "Prefiks : ".$pref5."<br>";
							echo "Infiks : - <br>";
							echo "Sufiks :  ".$suf1."<br>";
							echo "Confiks :  ".$pref5."+ - +".$suf1."<br>";
							echo "Ambifiks : - <br>";
							echo "Kata dasar : ".$trim_pref54.'<br>';
							echo "Pola kanonik : - <br>";
							echo "<hr>";
							}
						}

					// -na
					elseif(preg_match("/$suf4$/", substr($trim_pref5, -2))){
						$trim_suf4 = preg_replace("/$suf4$/", '', $trim_pref5);
						$fonem_suf4 = preg_replace($konsonan, 'K', $trim_suf4);
						$fonem_suf4kv = preg_replace($vokal, 'V', $fonem_suf4);

						// Cek pola kanonik
						if(in_array($fonem_suf4kv, $rules)){
							echo "Kata awal : ".$katajadi[$i].'<br>';
							echo "Pola fonem : ".$kv.'<br>';
							echo "Prefiks : ".$pref5."<br>";
							echo "Infiks : - <br>";
							echo "Sufiks :  ".$suf4."<br>";
							echo "Confiks :  ".$pref5."+ - +".$suf4."<br>";
							echo "Ambifiks : - <br>";
							echo "Kata dasar : ".$trim_suf4.'<br>';
							echo "Pola kanonik : ".$fonem_suf4kv."<br>";
							echo "<hr>";
							}
						else{	
							echo "Kata awal : ".$katajadi[$i].'<br>';
							echo "Pola fonem : ".$kv.'<br>';
							echo "Prefiks : ".$pref5."<br>";
							echo "Infiks : - <br>";
							echo "Sufiks :  ".$suf4."<br>";
							echo "Confiks :  ".$pref5."+ - +".$suf4."<br>";
							echo "Ambifiks : - <br>";
							echo "Kata dasar : ".$trim_suf4.'<br>';
							echo "Pola kanonik : - <br>";
							echo "<hr>";
							}
						}

					// -keun
					elseif(preg_match("/$suf3$/", substr($trim_pref5, -4))){
						$trim_suf3 = preg_replace("/$suf3$/", '', $trim_pref5);
						$fonem_suf3 = preg_replace($konsonan, 'K', $trim_suf3);
						$fonem_suf3kv = preg_replace($vokal, 'V', $fonem_suf3);

						// Cek pola kanonik
						if(in_array($fonem_suf3kv, $rules)){
							echo "Kata awal : ".$katajadi[$i].'<br>';
							echo "Pola fonem : ".$kv.'<br>';
							echo "Prefiks : ".$pref5."<br>";
							echo "Infiks : - <br>";
							echo "Sufiks :  ".$suf3."<br>";
							echo "Confiks :  ".$pref5."+ - +".$suf3."<br>";
							echo "Ambifiks : - <br>";
							echo "Kata dasar : ".$trim_suf3.'<br>';
							echo "Pola kanonik : ".$fonem_suf3kv."<br>";
							echo "<hr>";
							}
						else{	
							echo "Kata awal : ".$katajadi[$i].'<br>';
							echo "Pola fonem : ".$kv.'<br>';
							echo "Prefiks : ".$pref5."<br>";
							echo "Infiks : - <br>";
							echo "Sufiks :  ".$suf3."<br>";
							echo "Confiks :  ".$pref5."+ - +".$suf3."<br>";
							echo "Ambifiks : - <br>";
							echo "Kata dasar : ".$trim_suf3.'<br>';
							echo "Pola kanonik : - <br>";
							echo "<hr>";
							}
						}
					
					// -ka
					{
						$trim_suf3 = preg_replace("/^$pref5/", '', $trim_pref5);
						$fonem_suf3 = preg_replace($konsonan, 'K', $trim_suf3);
						$fonem_suf3kv = preg_replace($vokal, 'V', $fonem_suf3);

						// Cek pola kanonik
						if(in_array($fonem_suf3kv, $rules)){
							echo "Kata awal : ".$katajadi[$i].'<br>';
							echo "Pola fonem : ".$kv.'<br>';
							echo "Prefiks : ".$pref5."<br>";
							echo "Infiks : - <br>";
							echo "Sufiks : - <br>";
							echo "Confiks : <br>";
							echo "Ambifiks : - <br>";
							echo "Kata dasar : ".$trim_suf3.'<br>';
							echo "Pola kanonik : ".$fonem_suf3kv."<br>";
							echo "<hr>";
							}
						else{	
							echo "Kata awal : ".$katajadi[$i].'<br>';
							echo "Pola fonem : ".$kv.'<br>';
							echo "Prefiks : ".$pref5."<br>";
							echo "Infiks : - <br>";
							echo "Sufiks : - <br>";
							echo "Confiks : <br>";
							echo "Ambifiks : - <br>";
							echo "Kata dasar : ".$trim_suf3.'<br>';
							echo "Pola kanonik : - <br>";
							echo "<hr>";
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
								echo "Kata dasar: ".$katajadi[$i]."<br>";
								echo "Pola fonem: ".$kv."<br>";
								echo "Prefiks1 : ".$pref8."<br>";
								echo "Prefiks2 : ".$pref3."<br>";
								echo "Infiks : - <br>";
								echo "Sufiks : ".$suf2."<br>";
								echo "Confiks : ".$pref8.$pref3."+ - +".$suf2."<br>";
								echo "Ambifiks :  - <br>";
								echo "Kata dasar : ".$trim_pref83."<br>";
								echo "Pola kanonik : ".$fonem_pref83kv."<br>";
								echo "<hr>";
								}
							else{
								echo "Kata dasar: ".$katajadi[$i]."<br>";
								echo "Pola fonem: ".$kv."<br>";
								echo "Prefiks1 : ".$pref8."<br>";
								echo "Prefiks2 : ".$pref3."<br>";
								echo "Infiks : - <br>";
								echo "Sufiks : ".$suf2."<br>";
								echo "Confiks : ".$pref8.$pref3."+ - +".$suf2."<br>";
								echo "Ambifiks :  - <br>";
								echo "Kata dasar : ".$trim_pref83."<br>";
								echo "Pola kanonik : - <br>";
								echo "<hr>";
								}
							}
						// pi-ka
						else{
							// Cek pola kanonik
							if(in_array($fonem_pref82kv, $rules)){
								echo "Kata dasar: ".$katajadi[$i]."<br>";
								echo "Pola fonem: ".$kv."<br>";
								echo "Prefiks1 : ".$pref8."<br>";
								echo "Prefiks2 : ".$pref3."<br>";
								echo "Infiks : - <br>";
								echo "Sufiks : - <br>";
								echo "Confiks : ".$pref8.$pref3."<br>";
								echo "Ambifiks :  - <br>";
								echo "Kata dasar : ".$trim_pref82."<br>";
								echo "Pola kanonik : ".$fonem_pref82kv."<br>";
								echo "<hr>";
								}
							else{
								echo "Kata dasar: ".$katajadi[$i]."<br>";
								echo "Pola fonem: ".$kv."<br>";
								echo "Prefiks1 : ".$pref8."<br>";
								echo "Prefiks2 : ".$pref3."<br>";
								echo "Infiks : - <br>";
								echo "Sufiks : - <br>";
								echo "Confiks : ".$pref8.$pref3."<br>";
								echo "Ambifiks :  - <br>";
								echo "Kata dasar : ".$trim_pref82."<br>";
								echo "Pola kanonik : - <br>";
								echo "<hr>";
								}
							}
						}

					elseif(preg_match("/$suf2$/", substr($trim_pref81, -3))){
							$trim_suf2 = preg_replace("/$suf2$/", '', $trim_pref81);
							$fonem_suf2 = preg_replace($konsonan, 'K', $trim_suf2);
							$fonem_suf2kv = preg_replace($vokal, 'V', $fonem_suf2);

							// Cek pola kanonik
							if(in_array($fonem_suf2kv, $rules)){
								echo "Kata dasar: ".$katajadi[$i]."<br>";
								echo "Pola fonem: ".$kv."<br>";
								echo "Prefiks : ".$pref8."<br>";
								echo "Infiks : - <br>";
								echo "Sufiks : ".$suf2."<br>";
								echo "Confiks : ".$pref8."+ - +".$suf2."<br>";
								echo "Ambifiks :  - <br>";
								echo "Kata dasar : ".$trim_suf2."<br>";
								echo "Pola kanonik : ".$fonem_suf2kv."<br>";
								echo "<hr>";
								}
							else{
								echo "Kata dasar: ".$katajadi[$i]."<br>";
								echo "Pola fonem: ".$kv."<br>";
								echo "Prefiks : ".$pref8."<br>";
								echo "Infiks : - <br>";
								echo "Sufiks : ".$suf2."<br>";
								echo "Confiks : ".$pref8."+ - +".$suf2."<br>";
								echo "Ambifiks :  - <br>";
								echo "Kata dasar : ".$trim_suf2."<br>";
								echo "Pola kanonik : - <br>";
								echo "<hr>";
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
								echo "Kata dasar: ".$katajadi[$i]."<br>";
								echo "Pola fonem: ".$kv."<br>";
								echo "Prefiks : ".$pref8."<br>";
								echo "Infiks : - <br>";
								echo "Sufiks : ".$suf1."<br>";
								echo "Confiks : ".$pref8."+ - +".$suf1."<br>";
								echo "Ambifiks :  - <br>";
								echo "Kata dasar : ".$trim_suf1."<br>";
								echo "Pola kanonik : ".$fonem_suf1kv."<br>";
								echo "<hr>";
								}
							else{
								echo "Kata dasar: ".$katajadi[$i]."<br>";
								echo "Pola fonem: ".$kv."<br>";
								echo "Prefiks : ".$pref8."<br>";
								echo "Infiks : - <br>";
								echo "Sufiks : ".$suf1."<br>";
								echo "Confiks : ".$pref8."+ - +".$suf1."<br>";
								echo "Ambifiks :  - <br>";
								echo "Kata dasar : ".$trim_suf1."<br>";
								echo "Pola kanonik : - <br>";
								echo "<hr>";
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
							echo "Kata dasar: ".$katajadi[$i]."<br>";
							echo "Pola fonem: ".$kv."<br>";
							echo "Prefiks : ".$pref10."<br>";
							echo "Infiks : - <br>";
							echo "Sufiks : - <br>";
							echo "Confiks : - <br>";
							echo "Ambifiks :  - <br>";
							echo "Kata dasar : ".$trim_pref12."<br>";
							echo "Pola kanonik : ".$fonem_pref12kv."<br>";
							echo "<hr>";
							}
						else{
							echo "Kata dasar: ".$katajadi[$i]."<br>";
							echo "Pola fonem: ".$kv."<br>";
							echo "Prefiks : ".$pref10."<br>";
							echo "Infiks : - <br>";
							echo "Sufiks : - <br>";
							echo "Confiks : - <br>";
							echo "Ambifiks :  - <br>";
							echo "Kata dasar : ".$trim_pref12."<br>";
							echo "Pola kanonik : - <br>";
							echo "<hr>";
						}
					}

					// -eun
					elseif(preg_match("/$suf2$/", substr($trim_pref9, -3))){
							$trim_suf2 = preg_replace("/$suf2$/", '', $trim_pref9);
							$fonem_suf2 = preg_replace($konsonan, 'K', $trim_suf2);
							$fonem_suf2kv = preg_replace($vokal, 'V', $fonem_suf2);
						// Cek pola kanonik
						if(in_array($fonem_suf2kv, $rules)){
							echo "Kata dasar: ".$katajadi[$i]."<br>";
							echo "Pola fonem: ".$kv."<br>";
							echo "Prefiks : ".$pref9."<br>";
							echo "Infiks : - <br>";
							echo "Sufiks : ".$suf2."<br>";
							echo "Confiks : ".$pref9."+ - + ".$suf2."<br>";
							echo "Ambifiks :  - <br>";
							echo "Kata dasar : ".$trim_suf2."<br>";
							echo "Pola kanonik : ".$fonem_suf2kv."<br>";
							echo "<hr>";
							}
						else{
							echo "Kata dasar: ".$katajadi[$i]."<br>";
							echo "Pola fonem: ".$kv."<br>";
							echo "Prefiks : ".$pref9."<br>";
							echo "Infiks : - <br>";
							echo "Sufiks : ".$suf2."<br>";
							echo "Confiks : ".$pref9."+ - + ".$suf2."<br>";
							echo "Ambifiks :  - <br>";
							echo "Kata dasar : ".$trim_suf2."<br>";
							echo "Pola kanonik : - <br>";
							echo "<hr>";
							}
						}
					// -na
					elseif(preg_match("/$suf4$/", substr($trim_pref9, -2))){
						$trim_suf4 = preg_replace("/$suf4$/", '', $trim_pref9);
						$fonem_suf4 = preg_replace($konsonan, 'K', $trim_suf4);
						$fonem_suf4kv = preg_replace($vokal, 'V', $fonem_suf4);
						
						// Cek pola kanonik
						if(in_array($fonem_suf4kv, $rules)){
							echo "Kata dasar: ".$katajadi[$i]."<br>";
							echo "Pola fonem: ".$kv."<br>";
							echo "Prefiks : ".$pref9."<br>";
							echo "Infiks : - <br>";
							echo "Sufiks : ".$suf4."<br>";
							echo "Confiks : ".$pref9."+ - + ".$suf4."<br>";
							echo "Ambifiks :  - <br>";
							echo "Kata dasar : ".$trim_suf4."<br>";
							echo "Pola kanonik : ".$fonem_suf4kv."<br>";
							echo "<hr>";
							}
						else{
							echo "Kata dasar: ".$katajadi[$i]."<br>";
							echo "Pola fonem: ".$kv."<br>";
							echo "Prefiks : ".$pref9."<br>";
							echo "Infiks : - <br>";
							echo "Sufiks : ".$suf4."<br>";
							echo "Confiks : ".$pref9."+ - + ".$suf4."<br>";
							echo "Ambifiks :  - <br>";
							echo "Kata dasar : ".$trim_suf4."<br>";
							echo "Pola kanonik : - <br>";
							echo "<hr>";
							}
						}
					else{
						// Cek pola kanonik
						if(in_array($fonem_pref9kv, $rules)){
							echo "Kata dasar: ".$katajadi[$i]."<br>";
							echo "Pola fonem: ".$kv."<br>";
							echo "Prefiks : ".$pref9."<br>";
							echo "Infiks : - <br>";
							echo "Sufiks : - <br>";
							echo "Confiks : - <br>";
							echo "Ambifiks :  - <br>";
							echo "Kata dasar : ".$trim_pref9."<br>";
							echo "Pola kanonik : ".$fonem_pref9kv."<br>";
							echo "<hr>";
							}
						else{
							echo "Kata dasar: ".$katajadi[$i]."<br>";
							echo "Pola fonem: ".$kv."<br>";
							echo "Prefiks : ".$pref9."<br>";
							echo "Infiks : - <br>";
							echo "Sufiks : - <br>";
							echo "Confiks : - <br>";
							echo "Ambifiks :  - <br>";
							echo "Kata dasar : ".$trim_pref9."<br>";
							echo "Pola kanonik : - <br>";
							echo "<hr>";
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
								echo "Kata dasar: ".$katajadi[$i]."<br>";
								echo "Pola fonem: ".$kv."<br>";
								echo "Prefiks : ".$pref12."<br>";
								echo "Infiks : ".$inf1."<br>";
								echo "Sufiks : - <br>";
								echo "Confiks : - <br>";
								echo "Ambifiks :  - <br>";
								echo "Kata dasar : ".$trim_inf1."<br>";
								echo "Pola kanonik : ".$fonem_inf1kv."<br>";
								echo "<hr>";
								}
							else{
								echo "Kata dasar: ".$katajadi[$i]."<br>";
								echo "Pola fonem: ".$kv."<br>";
								echo "Prefiks : ".$pref12."<br>";
								echo "Infiks : ".$inf1."<br>";
								echo "Sufiks : - <br>";
								echo "Confiks : - <br>";
								echo "Ambifiks :  - <br>";
								echo "Kata dasar : ".$trim_inf1."<br>";
								echo "Pola kanonik : - <br>";
								echo "<hr>";
								}							
							}
						else{
						// Cek pola kanonik
						if(in_array($fonem_pref12kv, $rules)){
							echo "Kata dasar: ".$katajadi[$i]."<br>";
							echo "Pola fonem: ".$kv."<br>";
							echo "Prefiks : ".$pref12."<br>";
							echo "Infiks : - <br>";
							echo "Sufiks : - <br>";
							echo "Confiks : - <br>";
							echo "Ambifiks :  - <br>";
							echo "Kata dasar : ".$trim_pref12."<br>";
							echo "Pola kanonik : ".$fonem_pref12kv."<br>";
							echo "<hr>";
							}
						else{
							echo "Kata dasar: ".$katajadi[$i]."<br>";
							echo "Pola fonem: ".$kv."<br>";
							echo "Prefiks : ".$pref12."<br>";
							echo "Infiks : - <br>";
							echo "Sufiks : - <br>";
							echo "Confiks : - <br>";
							echo "Ambifiks :  - <br>";
							echo "Kata dasar : ".$trim_pref12."<br>";
							echo "Pola kanonik : - <br>";
							echo "<hr>";
							}
						}
					}
					else{
						// Cek pola kanonik
						if(in_array($fonem_pref4kv, $rules)){
							echo "Kata dasar: ".$katajadi[$i]."<br>";
							echo "Pola fonem: ".$kv."<br>";
							echo "Prefiks : ".$pref4."<br>";
							echo "Infiks : - <br>";
							echo "Sufiks : - <br>";
							echo "Confiks : - <br>";
							echo "Ambifiks :  - <br>";
							echo "Kata dasar : ".$trim_pref4."<br>";
							echo "Pola kanonik : ".$fonem_pref4kv."<br>";
							echo "<hr>";
							}
						else{
							echo "Kata dasar: ".$katajadi[$i]."<br>";
							echo "Pola fonem: ".$kv."<br>";
							echo "Prefiks : ".$pref4."<br>";
							echo "Infiks : - <br>";
							echo "Sufiks : - <br>";
							echo "Confiks : - <br>";
							echo "Ambifiks :  - <br>";
							echo "Kata dasar : ".$trim_pref4."<br>";
							echo "Pola kanonik : - <br>";
							echo "<hr>";
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
						echo "Kata dasar: ".$katajadi[$i]."<br>";
						echo "Pola fonem: ".$kv."<br>";
						echo "Prefiks : - <br>";
						echo "Infiks : ".$inf1."<br>";
						echo "Sufiks : - <br>";
						echo "Confiks : - <br>";
						echo "Ambifiks :  - <br>";
						echo "Kata dasar : ".$trim_inf1."<br>";
						echo "Pola kanonik : ".$fonem_inf1kv."<br>";
						echo "<hr>";
						}
					else{
						echo "Kata dasar: ".$katajadi[$i]."<br>";
						echo "Pola fonem: ".$kv."<br>";
						echo "Prefiks : - <br>";
						echo "Infiks : ".$inf1."<br>";
						echo "Sufiks : - <br>";
						echo "Confiks : - <br>";
						echo "Ambifiks :  - <br>";
						echo "Kata dasar : ".$trim_inf1."<br>";
						echo "Pola kanonik : - <br>";
						echo "<hr>";
						}
					}

				// Cek prefiks al-
				if(preg_match("/$inf2/", substr($katajadi[$i], 0))){
					$trim_inf2 = preg_replace("/$inf2/", '', $katajadi[$i]);
					$fonem_inf2 = preg_replace($konsonan, 'K', $trim_inf2);
					$fonem_inf2kv = preg_replace($vokal, 'V', $fonem_inf2);

					// Cek pola kanonik
					if(in_array($fonem_inf2kv, $rules)){
						echo "Kata dasar: ".$katajadi[$i]."<br>";
						echo "Pola fonem: ".$kv."<br>";
						echo "Prefiks : - <br>";
						echo "Infiks : ".$inf2."<br>";
						echo "Sufiks : - <br>";
						echo "Confiks : - <br>";
						echo "Ambifiks :  - <br>";
						echo "Kata dasar : ".$trim_inf2."<br>";
						echo "Pola kanonik : ".$fonem_inf2kv."<br>";
						echo "<hr>";
						}
					else{
						echo "Kata dasar: ".$katajadi[$i]."<br>";
						echo "Pola fonem: ".$kv."<br>";
						echo "Prefiks : - <br>";
						echo "Infiks : ".$inf2."<br>";
						echo "Sufiks : - <br>";
						echo "Confiks : - <br>";
						echo "Ambifiks :  - <br>";
						echo "Kata dasar : ".$trim_inf2."<br>";
						echo "Pola kanonik : - <br>";
						echo "<hr>";
						}
					}

				// Cek prefiks um-
				if(preg_match("/$inf3/", substr($katajadi[$i], 0))){
					$trim_inf3 = preg_replace("/$inf3/", '', $katajadi[$i]);
					$fonem_inf3 = preg_replace($konsonan, 'K', $trim_inf3);
					$fonem_inf3kv = preg_replace($vokal, 'V', $fonem_inf3);

					// Cek pola kanonik
					if(in_array($fonem_inf3kv, $rules)){
						echo "Kata dasar: ".$katajadi[$i]."<br>";
						echo "Pola fonem: ".$kv."<br>";
						echo "Prefiks : - <br>";
						echo "Infiks : ".$inf3."<br>";
						echo "Sufiks : - <br>";
						echo "Confiks : - <br>";
						echo "Ambifiks :  - <br>";
						echo "Kata dasar : ".$trim_inf3."<br>";
						echo "Pola kanonik : ".$fonem_inf3kv."<br>";
						echo "<hr>";
						}
					else{
						echo "Kata dasar: ".$katajadi[$i]."<br>";
						echo "Pola fonem: ".$kv."<br>";
						echo "Prefiks : - <br>";
						echo "Infiks : ".$inf3."<br>";
						echo "Sufiks : - <br>";
						echo "Confiks : - <br>";
						echo "Ambifiks :  - <br>";
						echo "Kata dasar : ".$trim_inf3."<br>";
						echo "Pola kanonik : - <br>";
						echo "<hr>";
						}
					}

				// Cek prefiks in-
				if(preg_match("/$inf4/", substr($katajadi[$i], 0))){
					$trim_inf4 = preg_replace("/$inf4/", '', $katajadi[$i]);
					$fonem_inf4 = preg_replace($konsonan, 'K', $trim_inf4);
					$fonem_inf4kv = preg_replace($vokal, 'V', $fonem_inf4);
					// Cek pola kanonik
					if(in_array($fonem_inf4kv, $rules)){
						echo "Kata dasar: ".$katajadi[$i]."<br>";
						echo "Pola fonem: ".$kv."<br>";
						echo "Prefiks : - <br>";
						echo "Infiks : ".$inf4."<br>";
						echo "Sufiks : - <br>";
						echo "Confiks : - <br>";
						echo "Ambifiks :  - <br>";
						echo "Kata dasar : ".$trim_inf4."<br>";
						echo "Pola kanonik : ".$fonem_inf4kv."<br>";
						echo "<hr>";
						}
					else{
						echo "Kata dasar: ".$katajadi[$i]."<br>";
						echo "Pola fonem: ".$kv."<br>";
						echo "Prefiks : - <br>";
						echo "Infiks : ".$inf4."<br>";
						echo "Sufiks : - <br>";
						echo "Confiks : - <br>";
						echo "Ambifiks :  - <br>";
						echo "Kata dasar : ".$trim_inf4."<br>";
						echo "Pola kanonik : - <br>";
						echo "<hr>";
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
							echo "Kata dasar: ".$katajadi[$i]."<br>";
							echo "Pola fonem: ".$kv."<br>";
							echo "Prefiks : - <br>";
							echo "Infiks : ".$inf1."<br>";
							echo "Sufiks : - <br>";
							echo "Confiks : - <br>";
							echo "Ambifiks :  - <br>";
							echo "Kata dasar : ".$trim_inf1."<br>";
							echo "Pola kanonik : ".$fonem_inf1kv."<br>";
							echo "<hr>";
							}
						else{
							echo "Kata dasar: ".$katajadi[$i]."<br>";
							echo "Pola fonem: ".$kv."<br>";
							echo "Prefiks : - <br>";
							echo "Infiks : ".$inf1."<br>";
							echo "Sufiks : - <br>";
							echo "Confiks : - <br>";
							echo "Ambifiks :  - <br>";
							echo "Kata dasar : ".$trim_inf1."<br>";
							echo "Pola kanonik : - <br>";
							echo "<hr>";
							}
						}
					else{
						// Cek pola kanonik
						if(in_array($fonem_inf1kv, $rules)){
							echo "Kata dasar: ".$katajadi[$i]."<br>";
							echo "Pola fonem: ".$kv."<br>";
							echo "Prefiks : - <br>";
							echo "Infiks : ".$inf1."<br>";
							echo "Sufiks : - <br>";
							echo "Confiks : - <br>";
							echo "Ambifiks :  - <br>";
							echo "Kata dasar : ".$trim_inf1."<br>";
							echo "Pola kanonik : ".$fonem_inf1kv."<br>";
							echo "<hr>";
							}
						else{
							echo "Kata dasar: ".$katajadi[$i]."<br>";
							echo "Pola fonem: ".$kv."<br>";
							echo "Prefiks : - <br>";
							echo "Infiks : ".$inf1."<br>";
							echo "Sufiks : - <br>";
							echo "Confiks : - <br>";
							echo "Ambifiks :  - <br>";
							echo "Kata dasar : ".$trim_inf1."<br>";
							echo "Pola kanonik : - <br>";
							echo "<hr>";
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
						echo "Kata dasar: ".$katajadi[$i]."<br>";
						echo "Pola fonem: ".$kv."<br>";
						echo "Prefiks : - <br>";
						echo "Infiks : ".$inf2."<br>";
						echo "Sufiks : - <br>";
						echo "Confiks : - <br>";
						echo "Ambifiks :  - <br>";
						echo "Kata dasar : ".$trim_inf2."<br>";
						echo "Pola kanonik : ".$fonem_inf2kv."<br>";
						echo "<hr>";
						}
					else{
						echo "Kata dasar: ".$katajadi[$i]."<br>";
						echo "Pola fonem: ".$kv."<br>";
						echo "Prefiks : - <br>";
						echo "Infiks : ".$inf2."<br>";
						echo "Sufiks : - <br>";
						echo "Confiks : - <br>";
						echo "Ambifiks :  - <br>";
						echo "Kata dasar : ".$trim_inf2."<br>";
						echo "Pola kanonik : - <br>";
						echo "<hr>";
						}
					}

				// Cek infiks in-
				if(preg_match("/$inf4/", substr($katajadi[$i], 0))){
					$trim_inf4 = preg_replace("/^$inf4/", '', $katajadi[$i]);
					$fonem_inf4 = preg_replace($konsonan, 'K', $trim_inf4);
					$fonem_inf4kv = preg_replace($vokal, 'V', $fonem_inf4);
					// Cek pola kanonik
					if(in_array($fonem_inf4kv, $rules)){
						echo "Kata dasar: ".$katajadi[$i]."<br>";
						echo "Pola fonem: ".$kv."<br>";
						echo "Prefiks : - <br>";
						echo "Infiks : ".$inf4."<br>";
						echo "Sufiks : - <br>";
						echo "Confiks : - <br>";
						echo "Ambifiks :  - <br>";
						echo "Kata dasar : ".$trim_inf4."<br>";
						echo "Pola kanonik : ".$fonem_inf4kv."<br>";
						echo "<hr>";
						}
					else{
						echo "Kata dasar: ".$katajadi[$i]."<br>";
						echo "Pola fonem: ".$kv."<br>";
						echo "Prefiks : - <br>";
						echo "Infiks : ".$inf4."<br>";
						echo "Sufiks : - <br>";
						echo "Confiks : - <br>";
						echo "Ambifiks :  - <br>";
						echo "Kata dasar : ".$trim_inf4."<br>";
						echo "Pola kanonik : - <br>";
						echo "<hr>";
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
							echo "Kata dasar: ".$katajadi[$i]."<br>";
							echo "Pola fonem: ".$kv."<br>";
							echo "Prefiks : - <br>";
							echo "Infiks : - <br>";
							echo "Sufiks : ".$suf3."<br>";
							echo "Confiks : - <br>";
							echo "Ambifiks :  - <br>";
							echo "Kata dasar : ".$trim_suf3."<br>";
							echo "Pola kanonik : ".$fonem_suf3kv."<br>";
							echo "<hr>";
							}
						else{
							echo "Kata dasar: ".$katajadi[$i]."<br>";
							echo "Pola fonem: ".$kv."<br>";
							echo "Prefiks : - <br>";
							echo "Infiks : - <br>";
							echo "Sufiks : ".$suf3."<br>";
							echo "Confiks : - <br>";
							echo "Ambifiks :  - <br>";
							echo "Kata dasar : ".$trim_suf3."<br>";
							echo "Pola kanonik : - <br>";
							echo "<hr>";
							}
						}
					else{
						// Cek pola kanonik
						if(in_array($fonem_suf2kv, $rules)){
							echo "Kata dasar: ".$katajadi[$i]."<br>";
							echo "Pola fonem: ".$kv."<br>";
							echo "Prefiks : - <br>";
							echo "Infiks : - <br>";
							echo "Sufiks : ".$suf2."<br>";
							echo "Confiks : - <br>";
							echo "Ambifiks :  - <br>";
							echo "Kata dasar : ".$trim_suf2."<br>";
							echo "Pola kanonik : ".$fonem_suf2kv."<br>";
							echo "<hr>";
							}
						else{
							echo "Kata dasar: ".$katajadi[$i]."<br>";
							echo "Pola fonem: ".$kv."<br>";
							echo "Prefiks : - <br>";
							echo "Infiks : - <br>";
							echo "Sufiks : ".$suf2."<br>";
							echo "Confiks : - <br>";
							echo "Ambifiks :  - <br>";
							echo "Kata dasar : ".$trim_suf2."<br>";
							echo "Pola kanonik : - <br>";
							echo "<hr>";
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
						echo "Kata dasar: ".$katajadi[$i]."<br>";
						echo "Pola fonem: ".$kv."<br>";
						echo "Prefiks : - <br>";
						echo "Infiks : - <br>";
						echo "Sufiks : ".$suf4."<br>";
						echo "Confiks : - <br>";
						echo "Ambifiks :  - <br>";
						echo "Kata dasar : ".$trim_suf4."<br>";
						echo "Pola kanonik : ".$fonem_suf4kv."<br>";
						echo "<hr>";
						}
					else{
						echo "Kata dasar: ".$katajadi[$i]."<br>";
						echo "Pola fonem: ".$kv."<br>";
						echo "Prefiks : - <br>";
						echo "Infiks : - <br>";
						echo "Sufiks : ".$suf4."<br>";
						echo "Confiks : - <br>";
						echo "Ambifiks :  - <br>";
						echo "Kata dasar : ".$trim_suf4."<br>";
						echo "Pola kanonik : - <br>";
						echo "<hr>";
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
							echo "Kata dasar: ".$katajadi[$i]."<br>";
							echo "Pola fonem: ".$kv."<br>";
							echo "Prefiks : - <br>";
							echo "Infiks : - <br>";
							echo "Sufiks : ".$suf6."<br>";
							echo "Confiks : - <br>";
							echo "Ambifiks :  - <br>";
							echo "Kata dasar : ".$trim_suf6."<br>";
							echo "Pola kanonik : ".$fonem_suf6kv."<br>";
							echo "<hr>";
							}
						else{
							echo "Kata dasar: ".$katajadi[$i]."<br>";
							echo "Pola fonem: ".$kv."<br>";
							echo "Prefiks : - <br>";
							echo "Infiks : - <br>";
							echo "Sufiks : ".$suf6."<br>";
							echo "Confiks : - <br>";
							echo "Ambifiks :  - <br>";
							echo "Kata dasar : ".$trim_suf6."<br>";
							echo "Pola kanonik : - <br>";
							echo "<hr>";
							}
						}
					else{
					// Cek pola kanonik
						if(in_array($fonem_suf5kv, $rules)){
							echo "Kata dasar: ".$katajadi[$i]."<br>";
							echo "Pola fonem: ".$kv."<br>";
							echo "Prefiks : - <br>";
							echo "Infiks : - <br>";
							echo "Sufiks : ".$suf5."<br>";
							echo "Confiks : - <br>";
							echo "Ambifiks :  - <br>";
							echo "Kata dasar : ".$trim_suf5."<br>";
							echo "Pola kanonik : ".$fonem_suf5kv."<br>";
							echo "<hr>";
							}
						else{
							echo "Kata dasar: ".$katajadi[$i]."<br>";
							echo "Pola fonem: ".$kv."<br>";
							echo "Prefiks : - <br>";
							echo "Infiks : - <br>";
							echo "Sufiks : ".$suf5."<br>";
							echo "Confiks : - <br>";
							echo "Ambifiks :  - <br>";
							echo "Kata dasar : ".$trim_suf5."<br>";
							echo "Pola kanonik : - <br>";
							echo "<hr>";
							}
						}
					} 

				}
			}
		}

?>