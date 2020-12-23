<?php

/**
 * File ini:
 *
 * Model untuk modul database
 *
 * donjo-app/models/migrations/Migrasi_fitur_premium_2101.php
 *
 */

/**
 *
 * File ini bagian dari:
 *
 * OpenSID
 *
 * Sistem informasi desa sumber terbuka untuk memajukan desa
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 *
 * Dengan ini diberikan izin, secara gratis, kepada siapa pun yang mendapatkan salinan
 * dari perangkat lunak ini dan file dokumentasi terkait ("Aplikasi Ini"), untuk diperlakukan
 * tanpa batasan, termasuk hak untuk menggunakan, menyalin, mengubah dan/atau mendistribusikan,
 * asal tunduk pada syarat berikut:

 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.

 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @package	OpenSID
 * @author	Tim Pengembang OpenDesa
 * @copyright	Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright	Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license	http://www.gnu.org/licenses/gpl.html	GPL V3
 * @link 	https://github.com/OpenSID/OpenSID
 */

class Migrasi_fitur_premium_2101 extends MY_model {

	public function up()
	{
		log_message('error', 'Jalankan ' . get_class($this));
		$hasil = true;

		// Tambahkan key sebutan_nip_desa
		$hasil =& $this->db->query("INSERT INTO setting_aplikasi (`key`, value, keterangan) VALUES ('sebutan_nip_desa', 'NIPD', 'Pengganti sebutan label niap/nipd')
			ON DUPLICATE KEY UPDATE value = VALUES(value), keterangan = VALUES(keterangan)");

		$list_setting =
			[
				[
					'key' => 'api_opendk_server',
					'value' => '',
					'keterangan' => 'Alamat Server OpenDK (contoh: https://demo.opendk.my.id)',
				],
				[
					'key' => 'api_opendk_key',
					'value' => '',
					'keterangan' => 'OpenDK API Key untuk Sinkronisasi Data',
				],
				[
					'key' => 'api_opendk_user',
					'value' => '',
					'keterangan' => 'Email Login Pengguna OpenDK',
				],
				[
					'key' => 'api_opendk_password',
					'value' => '',
					'keterangan' => 'Password Login Pengguna OpenDK',
				],
			];
		foreach ($list_setting as $setting)
		{
			$hasil =& $this->tambah_setting($setting);
		}

		// setting_aplikasi.valud diperpanjang
		$field = [
			'value' => [
				'type' => 'VARCHAR',
				'constraint' => 500,
				'null' => TRUE,
				'default' => NULL
			]
		];
		$hasil =& $this->dbforge->modify_column('setting_aplikasi', $field);

		$this->create_table_pembangunan_ref_jenis();
		$this->create_table_pembangunan_ref_sumber_dana();
		$this->create_table_pembangunan();
		$this->create_table_pembangunan_ref_dokumentasi();
		$this->add_modul_pembangunan();

		status_sukses($hasil);
		return $hasil;
	}

	protected function create_table_pembangunan_ref_jenis()
	{
		$this->dbforge->add_field([
			'id'         => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
			'jenis'      => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
			'keterangan' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
			'created_at' => ['type' => 'datetime', 'null' => true],
            'updated_at' => ['type' => 'datetime', 'null' => true],
		]);

		$this->dbforge->add_key('id', true);
		$this->dbforge->create_table('pembangunan_ref_jenis', true);
	}

	protected function create_table_pembangunan_ref_sumber_dana()
	{
		$this->dbforge->add_field([
			'id'          => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
			'sumber_dana' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
			'icon'        => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
			'created_at'  => ['type' => 'datetime', 'null' => true],
            'updated_at'  => ['type' => 'datetime', 'null' => true],
		]);

		$this->dbforge->add_key('id', true);
		$this->dbforge->create_table('pembangunan_ref_sumber_dana', true);
	}

	protected function create_table_pembangunan()
	{
		$this->dbforge->add_field([
			'id'                 => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
			'id_jenis'           => ['type' => 'INT', 'constraint' => 11],
			'id_sumber_dana'     => ['type' => 'INT', 'constraint' => 11],
			'judul'              => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
			'keterangan'         => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
			'lat'                => ['type' => 'VARCHAR','constraint' => 225, 'null' => true],
			'lng'                => ['type' => 'VARCHAR','constraint' => 255, 'null' => true],
			'volume'             => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
			'tahun_anggaran'     => ['type' => 'YEAR', 'null' => true],
			'pelaksana_kegiatan' => ['type' => 'VARCHAR','constraint' => 255,'null' => true],
			'status'             => ['type' => 'tinyint', 'constraint' => 1, 'default' => 1],
			'created_at'         => ['type' => 'datetime', 'null' => true],
            'updated_at'         => ['type' => 'datetime', 'null' => true],
		]);

		$this->dbforge->add_key('id', true);
		$this->dbforge->add_key('id_jenis');
		$this->dbforge->add_key('id_sumber_dana');
		$this->dbforge->create_table('pembangunan', true);
		$this->dbforge->add_column('pembangunan', ['CONSTRAINT fk_pembangunan_jenis FOREIGN KEY(id_jenis) REFERENCES pembangunan_ref_jenis(id) ON UPDATE CASCADE ON DELETE CASCADE']);
		$this->dbforge->add_column('pembangunan', ['CONSTRAINT fk_sumber_dana_pembangunan FOREIGN KEY(id_sumber_dana) REFERENCES pembangunan_ref_sumber_dana(id) ON UPDATE CASCADE ON DELETE CASCADE']);
	}

	protected function create_table_pembangunan_ref_dokumentasi()
	{
		$this->dbforge->add_field([
			'id'             => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
			'id_pembangunan' => ['type' => 'INT', 'constraint' => 11],
			'gambar'         => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
			'persentase'     => ['type' => 'int', 'constraint' => 3,'default' => 0],
			'keterangan'     => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
			'created_at'     => ['type' => 'datetime', 'null' => true],
            'updated_at'     => ['type' => 'datetime', 'null' => true],
		]);

		$this->dbforge->add_key('id', true);
		$this->dbforge->add_key('id_pembangunan');
		$this->dbforge->create_table('pembangunan_ref_dokumentasi', true);
		$this->dbforge->add_column('pembangunan_ref_dokumentasi', ['CONSTRAINT fk_pembangunan FOREIGN KEY(id_pembangunan) REFERENCES pembangunan(id) ON UPDATE CASCADE ON DELETE CASCADE']);
	}

	protected function add_modul_pembangunan()
	{
		$this->tambah_modul([
			'id'         => 220,
			'modul'      => 'Pembangunan',
			'url'        => 'pembangunan',
			'aktif'      => 1,
			'ikon'       => 'fa-institution',
			'urut'       => 9,
			'level'      => 2,
			'hidden'     => 0,
			'ikon_kecil' => 'fa-institution',
			'parent'     => 0
		]);

		$this->tambah_modul([
			'id'         => 221,
			'modul'      => 'Pembangunan Dokumentasi',
			'url'        => 'pembangunan_dokumentasi',
			'aktif'      => 1,
			'ikon'       => '',
			'urut'       => 0,
			'level'      => 0,
			'hidden'     => 0,
			'ikon_kecil' => '',
			'parent'     => 0
		]);

		$this->tambah_modul([
			'id'         => 222,
			'modul'      => 'Pembangunan Jenis',
			'url'        => 'pembangunan_jenis',
			'aktif'      => 1,
			'ikon'       => '',
			'urut'       => 0,
			'level'      => 0,
			'hidden'     => 0,
			'ikon_kecil' => '',
			'parent'     => 0
		]);

		$this->tambah_modul([
			'id'         => 223,
			'modul'      => 'Pembangunan Sumber Dana',
			'url'        => 'pembangunan_sumber_dana',
			'aktif'      => 1,
			'ikon'       => '',
			'urut'       => 0,
			'level'      => 0,
			'hidden'     => 0,
			'ikon_kecil' => '',
			'parent'     => 0
		]);
	}
}
