<?php
/* mod_satuan_barang.php ------------------------------------------------------
   	version: 1.01

	Part of AhadPOS : http://ahadpos.com
	License: GPL v2
			http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
			http://vlsm.org/etc/gpl-unofficial.id.html

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License v2 (links provided above) for more details.
----------------------------------------------------------------*/

check_user_access(basename($_SERVER['SCRIPT_NAME']));


switch($_GET[act]){
    // Tampil Satuan Barang
    default:
        echo "<h2>Tambah Satuan Barang</h2>
              <form method=POST action='./aksi.php?module=satuan_barang&act=input'>
              <table>
                <tr><td>Tambah Satuan</td><td> : <input type=text name='namaSatuanBarang' size=30></td></tr>
                <tr><td colspan=2 align=right><input type=submit value='Simpan'>&nbsp;&nbsp;&nbsp;
                                <input type=reset value='Batal'></td></tr>
              </table>
               </form>
              <br/>";
                // jumlah data yang akan ditampilkan per halaman
			$dataPerPage = 20;
			// apabila $_GET['page'] sudah didefinisikan, gunakan nomor halaman tersebut, 
			// sedangkan apabila belum, nomor halamannya 1.
			if(isset($_GET['page']))
			{
				$noPage = $_GET['page'];
			} 
			else $noPage = 1;
			// perhitungan offset
			$offset = ($noPage - 1) * $dataPerPage;
			// mencari jumlah semua data dalam tabel 
			$sql='SELECT COUNT(*) as jumData from satuan_barang';
			$result  = mysql_query($sql);
			$data     = mysql_fetch_assoc($result);
			$jumData = $data['jumData'];
			if(!isset($_GET['act']) && !isset($_GET['id'])){
				$sql="SELECT * from satuan_barang LIMIT ".$offset.", ".$dataPerPage."";
				$tampil=mysql_query($sql);
				$cek=mysql_num_rows($tampil);
				$no=1;
				?>
				<h2>Data Satuan Barang</h2>
              <table class=tableku>
              <tr><th>no</th><th width=400px>Satuan</th><th width=100px>aksi</th></tr>
				<?php
				if($cek==0)
				{
					echo "<tr><td colspan=6 class='data'>No data found in database</td></tr>";
				}
				else
				{
					while ($r=mysql_fetch_array($tampil)){
						//untuk mewarnai tabel menjadi selang-seling
						if(($no % 2) == 0){
							$warna = "#EAF0F7";
						}
						else{
							$warna = "#FFFFFF";
						}
						echo "<tr bgcolor=$warna>";//end warna
					   echo "<td class=td align=center>$no</td>
                         <td class=td >$r[namaSatuanBarang]</td>
                         <td class=td align=center><a href=?module=satuan_barang&page=".$_GET['page']."&act=editsatuan&id=$r[idSatuanBarang]>Edit</a> |
                         <a href=./aksi.php?module=satuan_barang&act=hapus&id=$r[idSatuanBarang]>Hapus</a>
                         </td></tr>";
					  $no++;
					}
				}
				// menentukan jumlah halaman yang muncul berdasarkan jumlah semua data
				$jumPage = ceil($jumData/$dataPerPage);
				echo "<div id='paging'>";
				// menampilkan link previous
				echo "Pages (".$jumPage.") : ";
				if ($noPage > 1) echo  "<a class='page' href='?module=satuan_barang&page=".($noPage-1)."'>&lt;Prev</a>";
				// memunculkan nomor halaman dan linknya
				$showPage=0;
				for($page = 1; $page <= $jumPage; $page++)
				{
					if ((($page >= $noPage - 3) && ($page <= $noPage + 3)) || ($page == 1) || ($page == $jumPage)) 
					{   
						if (($showPage == 1) && ($page != 2))
							echo "..."; 
						if (($showPage != ($jumPage - 1)) && ($page == $jumPage))
							echo "...";
						if ($page == $noPage)
							echo " <b>".$page."</b> ";
						else 
							echo " <a class='page' href='?module=satuan_barang&page=".$page."'>".$page."</a> ";
						$showPage = $page;          
					}
				}
				// menampilkan link next
				if ($noPage < $jumPage) echo "<a class='page' href='?module=satuan_barang&page=".($noPage+1)."'>Next&gt;</a>";
				echo "</div>
				</table>
				<p>&nbsp;</p>
                <a href=javascript:history.go(-1)><< Kembali</a>";
			}
        break;
        
    case "editsatuan":
        $edit = mysql_query("select * from satuan_barang where idSatuanBarang = '$_GET[id]'");
        $data = mysql_fetch_array($edit);
        echo "<h2>Edit Satuan Barang</h2>
            <form method=POST action='./aksi.php?module=satuan_barang&act=update' name='editsatuan'>
              <input type=hidden name='idSatuanBarang' value='$data[idSatuanBarang]'>
              <table>
                <tr><td>Edit Satuan</td><td> : <input type=text name='namaSatuanBarang' size=30 value='$data[namaSatuanBarang]'></td></tr>
                <tr><td colspan=2 align=right><input type=submit value='Simpan'>&nbsp;&nbsp;&nbsp;
                                <input type=button value=Batal onclick=self.history.back()></td></tr>
              </table>
               </form>
            <br/>";
        break;
}


/* CHANGELOG -----------------------------------------------------------

 1.0.1 / 2010-06-03 : Harry Sufehmi		: various enhancements, bugfixes
 0.6.5		    : Gregorius Arief		: initial release

------------------------------------------------------------------------ */

?>
