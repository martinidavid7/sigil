<?php

namespace App\Http\Controllers;

use App\Models\Bidding;
use App\Models\CityHall;
use App\Reports\BiddingsReport;
use App\Reports\StagesReport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Relatórios em PDF, abertos no navegador.
 */
class ReportController extends Controller
{
    public function biddings(Request $request): Response
    {
        $report = new BiddingsReport($request->validate(BiddingsReport::rules()));

        return $this->pdf('reports.biddings', [
            'report' => $report,
            'biddings' => $report->rows(),
        ], 'processos', 'landscape');
    }

    public function stages(Request $request): Response
    {
        $report = new StagesReport($request->validate(StagesReport::rules()));
        $stages = $report->rows();

        return $this->pdf('reports.stages', [
            'report' => $report,
            'stages' => $stages,
            'averages' => $report->averageDaysByStep($stages),
        ], 'etapas', 'landscape');
    }

    public function bidding(Bidding $bidding): Response
    {
        $bidding->load(['mode', 'stages.step', 'stages.secretary', 'stages.professional']);

        return $this->pdf('reports.bidding', [
            'bidding' => $bidding,
            'pendingSteps' => $bidding->pendingSteps(),
        ], 'processo-'.$bidding->number.'-'.$bidding->year);
    }

    private function pdf(string $view, array $data, string $filename, string $orientation = 'portrait'): Response
    {
        $pdf = Pdf::loadView($view, $data + ['cityHall' => CityHall::first()])
            ->setPaper('a4', $orientation)
            ->setOption('isFontSubsettingEnabled', true);

        // O total de páginas só é conhecido depois da renderização, então a
        // numeração é escrita direto no canvas (o CSS não suporta counter(pages)).
        $pdf->render();
        $dompdf = $pdf->getDomPDF();
        $canvas = $dompdf->getCanvas();
        $canvas->page_text(
            $canvas->get_width() - 104,
            $canvas->get_height() - 29,
            'Página {PAGE_NUM} de {PAGE_COUNT}',
            $dompdf->getFontMetrics()->getFont('DejaVu Sans'),
            7,
            [0.52, 0.53, 0.59],
        );

        return $pdf->stream($filename.'-'.now()->format('Y-m-d').'.pdf');
    }
}
