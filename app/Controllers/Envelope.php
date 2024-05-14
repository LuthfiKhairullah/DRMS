<?php

namespace App\Controllers;

use App\Models\M_Envelope;
use App\Models\M_EnvelopeInput;
use App\Models\M_MasterLine;
use App\Models\M_Plate;
use App\Models\M_Separator;
use App\Models\M_Team;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use function PHPUnit\Framework\countOf;

class Envelope extends BaseController
{
    protected $separatorModel;
    protected $envelopeModel;
    protected $plateModel;
    protected $envelopeinputModel;
    protected $teamModel;
    protected $masterLineModel;
    public function __construct()
    {
        $this->separatorModel = new M_Separator();
        $this->envelopeModel = new M_Envelope();
        $this->envelopeinputModel = new M_EnvelopeInput();
        $this->plateModel = new M_Plate();
        $this->teamModel = new M_Team();
        $this->masterLineModel = new M_MasterLine();
    }
    public function envelope_view($bulan = null)
    {
        if ($bulan == '') {
            $bulan = date('Y-m');
        }
        $session = \Config\Services::session();
        $envelope = $this->envelopeModel->where('MONTH(date)', date('m', strtotime($bulan)))->where('YEAR(date)', date('Y', strtotime($bulan)))->findAll();
        $envelopeinput = $this->envelopeinputModel->findAll();
        $team = $this->teamModel->select('UPPER(nama_pic) AS team, status')->orderBy('nama_pic', 'ASC')->findAll();
        $line = $this->masterLineModel->where('id_line <= 7')->orderBy('id_line', 'ASC')->findAll();
        $dates = array_column($envelope, "date");
        $lines = array_column($envelope, "line");
        $shift = array_column($envelope, "shift");
        array_multisort($dates, SORT_DESC, $lines, SORT_ASC, $shift, SORT_ASC,  $envelope);
        $status = $session->get();
        $data = [
            'envelope' => $envelope,
            'envelopeinput' => $envelopeinput,
            'session' => $status,
            'team' => $team,
            'line' => $line,
        ];
        return view('pages/envelope/envelope_view', $data);
    }

    public function detail_envelope($id)
    {
        $envelope = $this->envelopeModel->find($id);
        $envelopeinput = $this->envelopeinputModel->where('id_envelope', $id)->findAll();
        $plate = $this->plateModel->findAll();
        $separator = $this->separatorModel->findAll();
        $team = $this->teamModel->select('UPPER(nama_pic) AS team, status')->orderBy('nama_pic', 'ASC')->findAll();
        $line = $this->masterLineModel->where('id_line <= 7')->orderBy('id_line', 'ASC')->findAll();
        $data = [
            'plate' => $plate,
            'envelope' => $envelope,
            'envelopeinput' => $envelopeinput,
            'separator' => $separator,
            'team' => $team,
            'line' => $line,
        ];
        return view('pages/envelope/detail_envelope', $data);
    }

    public function save()
    {
        $id = $this->request->getVar('id_envelope');
        $envelopeinput = $this->envelopeinputModel->where('id_envelope', $id)->findAll();
        $id_envelopeinput = $this->request->getVar('id_envelopeinput');
        $date = $this->request->getVar('date');
        $line = $this->request->getVar('line');
        $shift = $this->request->getVar('shift');
        $team = $this->request->getVar('team');
        $plate = $this->request->getVar('plate');
        $hasil_produksi = $this->request->getVar('hasil_produksi');
        $separator = $this->request->getVar('separator');
        $melintir_bending = $this->request->getVar('melintir_bending');
        $terpotong = $this->request->getVar('terpotong');
        $rontok = $this->request->getVar('rontok');
        $tersangkut = $this->request->getVar('tersangkut');
        $melintir_bending_panel = $this->request->getVar('melintir_bending_panel');
        $terpotong_panel = $this->request->getVar('terpotong_panel');
        $rontok_panel = $this->request->getVar('rontok_panel');
        $tersangkut_panel = $this->request->getVar('tersangkut_panel');
        $persentase_reject_akumulatif = $this->request->getVar('persentase_reject_akumulatif');
        $envelopeinputnew = [];
        $data_new_envelopeinput = [];
        $data_old_envelopeinput = [];
        if ($id === NULL) {
            $envelope = $this->envelopeModel->findAll();
            $data_envelope[] = array(
                'date' => $date,
                'line' => $line,
                'shift' => $shift,
                'team' => $team,
            );
            $this->envelopeModel->insertBatch($data_envelope);
            $newid = $this->envelopeModel->insertID();
            return redirect()->to(base_url('envelope/detail_envelope/' . $newid));
        } else {
            $data_envelope[] = array(
                'id' => $id,
                'date' => $date,
                'line' => $line,
                'shift' => $shift,
                'team' => $team,
            );
            $this->envelopeModel->updateBatch($data_envelope, 'id');
            for ($i = 0; $i < ($id_envelopeinput !== NULL ? count($id_envelopeinput) : 0); $i++) {
                if ($id_envelopeinput[$i] === "" && $plate[$i] !== "") {
                    if ($line <= 3) {
                        $data_new_envelopeinput[] = array(
                            'id_envelope' => $id,
                            'plate' => $plate[$i],
                            'hasil_produksi' => $hasil_produksi[$i],
                            'separator' => $separator[$i] ?? NULL,
                            'melintir_bending_panel' => $melintir_bending_panel[$i] !== NULL ? $melintir_bending_panel[$i] : 0,
                            'terpotong_panel' => $terpotong_panel[$i] !== NULL ? $terpotong_panel[$i] : 0,
                            'rontok_panel' => $rontok_panel[$i] !== NULL ? $rontok_panel[$i] : 0,
                            'tersangkut_panel' => $tersangkut_panel[$i] !== NULL  ? $tersangkut_panel[$i] : 0,
                            'persentase_reject_akumulatif' => $persentase_reject_akumulatif[$i] !== NULL  ? $persentase_reject_akumulatif[$i] : 0,
                        );
                    } else {
                        $data_new_envelopeinput[] = array(
                            'id_envelope' => $id,
                            'plate' => $plate[$i],
                            'hasil_produksi' => $hasil_produksi[$i],
                            'separator' => $separator[$i] ?? NULL,
                            'melintir_bending' => $melintir_bending[$i] !== NULL ? $melintir_bending[$i] : 0,
                            'terpotong' => $terpotong[$i] !== NULL ? $terpotong[$i] : 0,
                            'rontok' => $rontok[$i] !== NULL ? $rontok[$i] : 0,
                            'tersangkut' => $tersangkut[$i] !== NULL  ? $tersangkut[$i] : 0,
                            'melintir_bending_panel' => $melintir_bending_panel[$i] !== NULL ? $melintir_bending_panel[$i] : 0,
                            'terpotong_panel' => $terpotong_panel[$i] !== NULL ? $terpotong_panel[$i] : 0,
                            'rontok_panel' => $rontok_panel[$i] !== NULL ? $rontok_panel[$i] : 0,
                            'tersangkut_panel' => $tersangkut_panel[$i] !== NULL  ? $tersangkut_panel[$i] : 0,
                            'persentase_reject_akumulatif' => $persentase_reject_akumulatif[$i] !== NULL  ? $persentase_reject_akumulatif[$i] : 0,
                        );
                    }
                } else {
                    $envelopeinputnew[$id_envelopeinput[$i]] = $id_envelopeinput[$i];
                    if ($line <= 3) {
                        $data_old_envelopeinput[] = array(
                            'id' => $id_envelopeinput[$i],
                            'plate' => $plate[$i],
                            'hasil_produksi' => $hasil_produksi[$i],
                            'separator' => $separator[$i],
                            'melintir_bending_panel' => $melintir_bending_panel[$i] !== NULL ? $melintir_bending_panel[$i] : 0,
                            'terpotong_panel' => $terpotong_panel[$i] !== NULL ? $terpotong_panel[$i] : 0,
                            'rontok_panel' => $rontok_panel[$i] !== NULL ? $rontok_panel[$i] : 0,
                            'tersangkut_panel' => $tersangkut_panel[$i] !== NULL  ? $tersangkut_panel[$i] : 0,
                            'persentase_reject_akumulatif' => $persentase_reject_akumulatif[$i] !== NULL  ? $persentase_reject_akumulatif[$i] : 0,
                        );
                    } else {
                        $data_old_envelopeinput[] = array(
                            'id' => $id_envelopeinput[$i],
                            'plate' => $plate[$i],
                            'hasil_produksi' => $hasil_produksi[$i],
                            'separator' => $separator[$i],
                            'melintir_bending' => $melintir_bending[$i] !== NULL ? $melintir_bending[$i] : 0,
                            'terpotong' => $terpotong[$i] !== NULL ? $terpotong[$i] : 0,
                            'rontok' => $rontok[$i] !== NULL ? $rontok[$i] : 0,
                            'tersangkut' => $tersangkut[$i] !== NULL  ? $tersangkut[$i] : 0,
                            'melintir_bending_panel' => $melintir_bending_panel[$i] !== NULL ? $melintir_bending_panel[$i] : 0,
                            'terpotong_panel' => $terpotong_panel[$i] !== NULL ? $terpotong_panel[$i] : 0,
                            'rontok_panel' => $rontok_panel[$i] !== NULL ? $rontok_panel[$i] : 0,
                            'tersangkut_panel' => $tersangkut_panel[$i] !== NULL  ? $tersangkut_panel[$i] : 0,
                            'persentase_reject_akumulatif' => $persentase_reject_akumulatif[$i] !== NULL  ? $persentase_reject_akumulatif[$i] : 0,
                        );
                    }
                }
            }
            if (count($data_new_envelopeinput) > 0) {
                $this->envelopeinputModel->insertBatch($data_new_envelopeinput);
            }
            if (count($data_old_envelopeinput) > 0) {
                $this->envelopeinputModel->updateBatch($data_old_envelopeinput, 'id');
            }
        }
        foreach ($envelopeinput as $ei) {
            if ($envelopeinputnew !== NULL) {
                if (!array_key_exists($ei['id'], $envelopeinputnew)) {
                    $this->envelopeinputModel->delete($ei['id']);
                }
            } else {
                $this->envelopeinputModel->delete($ei['id']);
            }
        }
        return redirect()->to(base_url('envelope/detail_envelope/' . $id));
    }

    public function delete_envelope()
    {
        $id_envelope = $this->request->getVar('id_envelope');
        $this->envelopeModel->delete(['id' => $id_envelope]);
        $this->envelopeinputModel->delete(['id_envelope' => $id_envelope]);

        return redirect()->to(base_url('/envelope'));
    }

    public function download()
    {
        $start_date = $this->request->getPost('start_date');
        $end_date = $this->request->getPost('end_date');
        $envelope = $this->envelopeModel->where('date >=', $start_date)->where('date <=', $end_date)->findAll();
        $envelopeinput = $this->envelopeinputModel->findAll();
        $dates = array_column($envelope, "date");
        $lines = array_column($envelope, "line");
        $shift = array_column($envelope, "shift");
        array_multisort($lines, SORT_ASC, $dates, SORT_ASC, $shift, SORT_ASC,  $envelope);
        // Membuat objek Spreadsheet baru
        $spreadsheet = new Spreadsheet();

        // Menambahkan data ke worksheet
        $sheet = $spreadsheet->getActiveSheet();
        $data = array(
            array('', '', '', '', '', '', '', 'Jumlah NG (Kg)', '', '', '', 'Jumlah NG (Panel)'),
            array('Date', 'Line', 'Shift', 'Team', 'Hasil Produksi', 'Type Plate', 'Separator', 'Melintir Bending', 'Terpotong', 'Rontok', 'Tersangkut', 'Melintir Bending', 'Terpotong', 'Rontok', 'Tersangkut', 'Persentase Reject Akumulatif')
        );
        $sheet->mergeCells('H1:K1');
        $sheet->mergeCells('L1:O1');
        $isExist = [];
        foreach ($envelope as $envl) {
            if (!array_key_exists($envl['id'], $isExist)) {
                foreach ($envelopeinput as $ei) {
                    if ($envl['id'] === $ei['id_envelope']) {
                        $isExist[$envl['id']] = $envl['id'];
                        $data[] = array($envl['date'], $envl['line'], $envl['shift'], $envl['team'], $ei['hasil_produksi'], $ei['plate'], $ei['separator'], $ei['melintir_bending'], $ei['terpotong'], $ei['rontok'], $ei['tersangkut'], $ei['melintir_bending_panel'], $ei['terpotong_panel'], $ei['rontok_panel'], $ei['tersangkut_panel'], $ei['persentase_reject_akumulatif']);
                    }
                }
            }
        };

        // Memasukkan data array ke dalam worksheet
        $sheet->fromArray($data);

        // Mengatur header respons HTTP
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="data.xlsx"');
        header('Cache-Control: max-age=0');

        ob_end_clean();
        // Membuat objek Writer untuk menulis spreadsheet ke output
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit();
    }
}
