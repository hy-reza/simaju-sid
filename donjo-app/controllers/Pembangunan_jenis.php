<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * File ini:
 *
 * Controller untuk modul Pembangunan
 *
 * donjo-app/controllers/Pembangunan_jenis.php
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
 *
 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.
 *
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

class Pembangunan_jenis extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->set_minsidebar(1);
        $this->tab_ini = 3;

        $this->load->model('pembangunan_jenis_model', 'model');
    }

    public function index()
    {
        if ($this->input->is_ajax_request()) {
            $start = $this->input->get('start');
            $length = $this->input->get('length');
            $search = $this->input->get('search[value]');
            $order = $this->model::ORDER_ABLE[$this->input->get('order[0][column]')];
            $dir = $this->input->get('order[0][dir]');

            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'draw'            => $this->input->get('draw'),
                    'recordsTotal'    => $this->model->get_data()->count_all_results(),
                    'recordsFiltered' => $this->model->get_data($search)->count_all_results(),
                    'data'            => $this->model->get_data($search)->order_by($order, $dir)->limit($length, $start)->get()->result(),
                ]));
        }

        $this->render('pembangunan/pembangunan_jenis_index');
    }

    public function show($id)
    {
        $data = $this->model->find($id);

        if (is_null($data)) {
            show_404();
        }

        $this->render('pembangunan/jenis/jenis_show', [
            'main' => $data,
        ]);
    }

    public function new()
    {
        $this->render('pembangunan/jenis/jenis_form');
    }

    public function create()
    {
        $this->model->insert($this->input->post());

        if ($this->db->affected_rows()) {
            $this->session->success = 1;
        } else {
            $this->session->success = -1;

            redirect('pembangunan_jenis/new');
        }

        redirect('pembangunan_jenis');
    }

    public function edit($id)
    {
        $data = $this->model->find($id);

        if (is_null($data)) {
            show_404();
        }

        $this->render('pembangunan/jenis/jenis_edit', [
            'main' => $data,
        ]);
    }

    public function update($id)
    {
        $this->model->update($id, $this->input->post());

        if ($this->db->affected_rows()) {
            $this->session->success = 1;
        } else {
            $this->session->success = -1;

            redirect("pembangunan_jenis/edit/{$id}");
        }

        redirect('pembangunan_jenis');
    }

    public function delete($id)
    {
        $this->model->delete($id);

        if ($this->db->affected_rows()) {
            $this->session->success = 1;
        } else {
            $this->session->success = -1;
        }

        redirect('pembangunan_jenis');
    }
}