<?php

    function getValue($select, $from, $where){
        $sql = DB::select("select " . $select . " as nm_field from " . $from . " where " . $where);
        return $sql[0]->nm_field;

    }

    function countValue($from, $where=null){
        if($where != ""){
            $sql = DB::select("select count(*) as nm_field from " . $from . " where " . $where);
        }else{
            $sql = DB::select("select count(*) as nm_field from " . $from);
        }

        return $sql[0]->nm_field;
	};

    function indo_date($date) {
        $newdate = new DateTime($date);
        $pcs = explode("-", $date);
        $y = $newdate->format('Y');
        $m = $newdate->format('n');
        $d = $newdate->format('j');
        $wk = $newdate->format('w');

        $getbulan = array ();
        $getbulan[1] = 'Januari';
        $getbulan[2] = 'Februari';
        $getbulan[3] = 'Maret';
        $getbulan[4] = 'April';
        $getbulan[5] = 'Mei';
        $getbulan[6] = 'Juni';
        $getbulan[7] = 'Juli';
        $getbulan[8] = 'Agustus';
        $getbulan[9] = 'September';
        $getbulan[10] = 'Oktober';
        $getbulan[11] = 'November';
        $getbulan[12] = 'Desember';

        $gethari = array ();
        $gethari[0] = 'Minggu';
        $gethari[1] = 'Senin';$gethari[2] = 'Selasa';$gethari[3] = 'Rabu';
        $gethari[4] = 'Kamis';$gethari[5] = 'Jumat';$gethari[6] = 'Sabtu';

        return $gethari[$wk]. ", ". $d ." ". $getbulan[$m] ." ". $y;
    }

    function nama_bulan($m){
		if (trim($m) != '' AND $m != '0') {
			$getbulan = array ();
			$getbulan[1] = 'Januari';
			$getbulan[2] = 'Februari';
			$getbulan[3] = 'Maret';
			$getbulan[4] = 'April';
			$getbulan[5] = 'Mei';
			$getbulan[6] = 'Juni';
			$getbulan[7] = 'Juli';
			$getbulan[8] = 'Agustus';
			$getbulan[9] = 'September';
			$getbulan[10] = 'Oktober';
			$getbulan[11] = 'November';
			$getbulan[12] = 'Desember';

			return $getbulan[(int)$m];
		}
	}

    function indo_dates($date){
        $newdate = new DateTime($date);
        $pcs = explode("-", $date);
        $y = $newdate->format('Y');
        $m = $newdate->format('n');
        $d = $newdate->format('j');
        $wk = $newdate->format('w');

        $getbulan = array ();
        $getbulan[1] = 'Januari';
        $getbulan[2] = 'Februari';
        $getbulan[3] = 'Maret';
        $getbulan[4] = 'April';
        $getbulan[5] = 'Mei';
        $getbulan[6] = 'Juni';
        $getbulan[7] = 'Juli';
        $getbulan[8] = 'Agustus';
        $getbulan[9] = 'September';
        $getbulan[10] = 'Oktober';
        $getbulan[11] = 'November';
        $getbulan[12] = 'Desember';

        $gethari = array ();
        $gethari[0] = 'Minggu';
        $gethari[1] = 'Senin';$gethari[2] = 'Selasa';$gethari[3] = 'Rabu';
        $gethari[4] = 'Kamis';$gethari[5] = 'Jumat';$gethari[6] = 'Sabtu';

        return $d ." ". $getbulan[$m] ." ". $y;
    }
