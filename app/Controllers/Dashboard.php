<?php

namespace App\Controllers;

use App\Models\ProgramKerjaModel;

/**
 * Controller: Dashboard
 * 
 * Dashboard utama aplikasi PKPT dengan statistik, kalender, dan grafik
 * 
 * @author PKPT Development Team
 * @created 2026-01-12
 */
class Dashboard extends BaseController
{
    protected $programKerjaModel;

    public function __construct()
    {
        $this->programKerjaModel = new ProgramKerjaModel();
    }

    /**
     * Halaman utama dashboard
     * 
     * @return string
     */
    public function index()
    {
        $year = $this->request->getGet('year');
        if (!$year) {
            $year = session()->get('pkpt_tahun_aktif') ?? date('Y');
        }
        
        $data['judul'] = 'Dashboard PKPT';
        $data['statistik'] = $this->programKerjaModel->ambilStatistik($year);
        $data['available_years'] = $this->programKerjaModel->getYears();
        $data['tahun_aktif'] = $year;
        
        return view('dashboard/dashboard', $data);
    }

    /**
     * API: Ambil data kalender (AJAX)
     * 
     * @return ResponseInterface
     */
    public function getCalendarData()
    {
        $year = $this->request->getGet('year');
        if (!$year) {
            $year = session()->get('pkpt_tahun_aktif') ?? date('Y');
        }
        $month = $this->request->getGet('month') ?? date('m');
        
        $activities = $this->programKerjaModel->getActivitiesByMonth($year, $month);
        
        // Format data untuk kalender
        $events = [];
        foreach ($activities as $activity) {
            $events[] = [
                'id' => $activity['id'],
                'title' => $activity['nama_kegiatan'],
                'start' => $activity['tanggal_mulai'],
                'end' => $activity['tanggal_selesai'],
                'status' => $activity['status'],
                'color' => $this->getStatusColor($activity['status'])
            ];
        }
        
        return $this->response->setJSON([
            'success' => true,
            'data' => $events
        ]);
    }

    /**
     * API: Ambil data untuk grafik (AJAX)
     * 
     * @return ResponseInterface
     */
    public function getChartData()
    {
        $year = $this->request->getGet('year');
        if (!$year) {
            $year = session()->get('pkpt_tahun_aktif') ?? date('Y');
        }
        
        $data = [
            'status_distribution' => $this->programKerjaModel->getStatusDistribution($year),
            'monthly_trend' => $this->programKerjaModel->getMonthlyTrend($year),
            'budget_comparison' => $this->programKerjaModel->getBudgetComparison($year),
            'monthly_status_distribution' => $this->programKerjaModel->getMonthlyStatusDistribution($year)
        ];
        
        return $this->response->setJSON([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * API: Ambil data statistik (AJAX)
     * 
     * @return ResponseInterface
     */
    public function getStatistics()
    {
        $year = $this->request->getGet('year');
        if (!$year) {
            $year = session()->get('pkpt_tahun_aktif') ?? date('Y');
        } else {
            // Sync year back to session if explicitly changed
            session()->set('pkpt_tahun_aktif', $year);
        }
        
        $statistik = $this->programKerjaModel->ambilStatistik($year);
        
        return $this->response->setJSON([
            'success' => true,
            'data' => $statistik
        ]);
    }


    /**
     * Helper: Tentukan warna berdasarkan status
     * 
     * @param string $status
     * @return string
     */
    private function getStatusColor($status)
    {
        switch ($status) {
            case 'Terlaksana':
                return '#28a745'; // Green
            case 'Tidak Terlaksana':
                return '#dc3545'; // Red
            case 'Penugasan Tambahan':
                return '#007bff'; // Blue
            default:
                return '#6c757d'; // Gray
        }
    }
}
