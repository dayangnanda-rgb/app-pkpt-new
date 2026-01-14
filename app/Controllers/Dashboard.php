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
        $data['judul'] = 'Dashboard PKPT';
        $data['statistik'] = $this->programKerjaModel->ambilStatistik();
        $data['upcoming'] = $this->programKerjaModel->getUpcomingActivities(5);
        
        return view('dashboard/dashboard', $data);
    }

    /**
     * API: Ambil data kalender (AJAX)
     * 
     * @return ResponseInterface
     */
    public function getCalendarData()
    {
        $year = $this->request->getGet('year') ?? date('Y');
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
        $year = $this->request->getGet('year') ?? date('Y');
        
        $data = [
            'status_distribution' => $this->programKerjaModel->getStatusDistribution(),
            'monthly_trend' => $this->programKerjaModel->getMonthlyTrend($year),
            'budget_comparison' => $this->programKerjaModel->getBudgetComparison()
        ];
        
        return $this->response->setJSON([
            'success' => true,
            'data' => $data
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
