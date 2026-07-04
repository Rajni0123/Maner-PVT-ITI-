<?php

namespace App\Core;

require_once base_path('app/Lib/fpdf.php');

class FeeStructurePdf extends \FPDF
{
    public static function download(): void
    {
        $pdf = new self('P', 'mm', 'A4');
        $pdf->build();
        $pdf->Output('D', 'Maner-Private-ITI-Fee-Structure-2026-28.pdf');
        exit;
    }

    public function build(): void
    {
        $header = \App\Models\SiteData::header();
        $settings = \App\Models\SiteData::settings();
        $logoText = $header['logo_text'] ?? 'Maner Private ITI';
        if ($logoText === 'Maner Pvt ITI') {
            $logoText = 'Maner Private ITI';
        }
        $phone = $header['phone'] ?? '';
        $email = $header['email'] ?? '';
        $misCode = $settings['mis_code'] ?? 'PR10001156';
        $bankName = $settings['fee_bank_name'] ?? '';
        $bankAddress = $settings['fee_bank_address'] ?? '';
        $bankHolder = $settings['fee_bank_holder'] ?? $logoText;
        $bankAccount = $settings['fee_bank_account'] ?? '';
        $bankIfsc = $settings['fee_bank_ifsc'] ?? '';

        $this->SetAutoPageBreak(true, 15);
        $this->SetMargins(14, 14, 14);
        $this->AddPage();
        $this->SetCreator($logoText);
        $this->SetAuthor($logoText);
        $this->SetTitle('Fee Structure 2026-28 - ' . $logoText, true);

        // Header bar
        $this->SetFillColor(19, 27, 46);
        $this->Rect(0, 0, 210, 28, 'F');
        $this->SetTextColor(255, 255, 255);
        $this->SetFont('Helvetica', 'B', 16);
        $this->SetXY(14, 8);
        $this->Cell(182, 8, $this->t($logoText), 0, 2, 'C');
        $this->SetFont('Helvetica', '', 9);
        $this->SetTextColor(254, 166, 25);
        $this->Cell(182, 5, 'NCVT Affiliated ITI  |  MIS CODE: ' . $this->t($misCode), 0, 1, 'C');

        $this->SetY(34);
        $this->SetTextColor(100, 116, 139);
        $this->SetFont('Helvetica', '', 9);
        $contact = [];
        if ($phone !== '') {
            $contact[] = 'Phone: ' . $this->t(format_mobile($phone));
        }
        if ($email !== '') {
            $contact[] = 'Email: ' . $this->t($email);
        }
        if ($contact) {
            $this->Cell(182, 5, implode('   |   ', $contact), 0, 1, 'C');
        }

        $this->Ln(2);
        $this->SetFillColor(19, 27, 46);
        $this->SetTextColor(254, 166, 25);
        $this->SetFont('Helvetica', 'B', 10);
        $this->Cell(182, 7, 'FOR SESSION AUG 2026 - 2028', 0, 1, 'C', true);

        $this->Ln(4);
        $this->SetTextColor(19, 27, 46);
        $this->SetFont('Helvetica', 'B', 12);
        $this->Cell(182, 7, 'Government Prescribed Course Fee', 0, 1, 'C');
        $this->SetFont('Helvetica', '', 9);
        $this->SetTextColor(71, 85, 105);
        $this->Cell(182, 5, '(Sarkar dwara nirdharit course shulk)', 0, 1, 'C');
        $this->Ln(3);

        $rows = [
            ['Trade', 'Electrician & Fitter'],
            ['Duration of Course', '2 Years (August to July)'],
            ['Prospectus & Admission Form', 'Rs. 200/-'],
            ['Tuition Fee (at enrollment)', 'Rs. 10,000/-'],
            ['Tuition Fee (6 x Rs. 7,000 every 3 months)', 'Rs. 42,000/-'],
            ['Total Tuition Fee (2-Year Course)', 'Rs. 52,200/-'],
            ['AITT Exam Fee (NCVT)', 'Rs. 500/- per year as per NCVT orders'],
        ];

        $this->SetFont('Helvetica', 'B', 9);
        $this->SetFillColor(19, 27, 46);
        $this->SetTextColor(255, 255, 255);
        $this->Cell(110, 8, 'Particulars', 1, 0, 'L', true);
        $this->Cell(72, 8, 'Amount', 1, 1, 'L', true);

        $this->SetTextColor(15, 23, 42);
        foreach ($rows as $i => [$label, $value]) {
            $fill = ($i === 5);
            if ($fill) {
                $this->SetFillColor(255, 247, 237);
                $this->SetFont('Helvetica', 'B', 9);
            } else {
                $this->SetFillColor(255, 255, 255);
                $this->SetFont('Helvetica', '', 9);
            }
            $this->Cell(110, 8, $this->t($label), 1, 0, 'L', true);
            $this->Cell(72, 8, $this->t($value), 1, 1, 'L', true);
        }

        $this->Ln(6);
        $this->SetFont('Helvetica', 'B', 11);
        $this->SetTextColor(19, 27, 46);
        $this->Cell(182, 7, 'Installment Payment Schedule', 0, 1, 'L');
        $this->SetDrawColor(254, 166, 25);
        $this->SetLineWidth(0.6);
        $this->Line(14, $this->GetY(), 196, $this->GetY());
        $this->Ln(3);

        $yStart = $this->GetY();
        $this->installmentTable(14, $yStart, 88, '1st Year Installments', [
            ['1st Installment', '01 Oct', '15 Oct'],
            ['2nd Installment', '01 Jan', '15 Jan'],
            ['3rd Installment', '01 Apr', '15 Apr'],
        ]);
        $this->installmentTable(108, $yStart, 88, '2nd Year Installments', [
            ['4th Installment', '01 Oct', '15 Oct'],
            ['5th Installment', '01 Jan', '15 Jan'],
            ['6th Installment', '01 Apr', '15 Apr'],
        ]);

        $this->SetY($yStart + 42);
        $this->Ln(4);
        $this->SetFont('Helvetica', 'B', 11);
        $this->SetTextColor(19, 27, 46);
        $this->Cell(182, 7, 'Institute Bank Account Details', 0, 1, 'L');
        $this->SetDrawColor(254, 166, 25);
        $this->Line(14, $this->GetY(), 196, $this->GetY());
        $this->Ln(3);

        $this->SetFillColor(248, 250, 252);
        $this->SetDrawColor(203, 213, 225);
        $this->SetTextColor(30, 41, 59);
        $this->SetFont('Helvetica', '', 9);
        if ($bankAccount !== '' && $bankIfsc !== '') {
            $bankLines = [
                'Bank Name: ' . $bankName . ($bankAddress !== '' ? ', ' . $bankAddress : ''),
                'Account Holder: ' . $bankHolder,
                'Account No: ' . $bankAccount,
                'IFSC Code: ' . $bankIfsc,
            ];
        } else {
            $bankLines = [
                'Please contact the institute office for bank account details.',
                ($phone !== '' ? 'Phone: ' . format_mobile($phone) : ''),
                ($email !== '' ? 'Email: ' . $email : ''),
            ];
            $bankLines = array_values(array_filter($bankLines));
        }
        $boxH = 8 + (count($bankLines) * 6);
        $this->Rect(14, $this->GetY(), 182, $boxH, 'DF');
        $y = $this->GetY() + 3;
        foreach ($bankLines as $line) {
            $this->SetXY(18, $y);
            $this->Cell(174, 6, $this->t($line), 0, 1, 'L');
            $y += 6;
        }
        $this->SetY($y + 4);

        $this->SetFont('Helvetica', '', 8);
        $this->SetTextColor(100, 116, 139);
        $this->MultiCell(182, 4, $this->t(
            'Note: AITT exam fee is payable as per NCVT orders (Rs. 500 per year). Total tuition fee for the 2-year course is Rs. 52,200/- including prospectus and enrollment fee.'
        ), 0, 'L');

        $this->Ln(6);
        $this->SetDrawColor(226, 232, 240);
        $this->Line(14, $this->GetY(), 196, $this->GetY());
        $this->Ln(3);
        $this->SetFont('Helvetica', '', 8);
        $this->SetTextColor(100, 116, 139);
        $this->Cell(182, 4, $this->t(strtoupper($logoText)) . '  |  Fee Structure Session 2026-2028', 0, 1, 'C');
        $this->Cell(182, 4, 'Computer Generated PDF Document - Valid with Official Seal of the Institute', 0, 1, 'C');
    }

    private function installmentTable(float $x, float $y, float $w, string $title, array $rows): void
    {
        $this->SetXY($x, $y);
        $this->SetFillColor(19, 27, 46);
        $this->SetTextColor(255, 255, 255);
        $this->SetFont('Helvetica', 'B', 9);
        $this->Cell($w, 7, $this->t($title), 1, 2, 'L', true);

        $this->SetX($x);
        $this->SetFillColor(241, 245, 249);
        $this->SetTextColor(51, 65, 85);
        $this->SetFont('Helvetica', 'B', 8);
        $this->Cell($w * 0.42, 6, 'Installment', 1, 0, 'L', true);
        $this->Cell($w * 0.29, 6, 'From', 1, 0, 'L', true);
        $this->Cell($w * 0.29, 6, 'To', 1, 1, 'L', true);

        $this->SetFont('Helvetica', '', 8);
        $this->SetTextColor(15, 23, 42);
        foreach ($rows as $row) {
            $this->SetX($x);
            $this->Cell($w * 0.42, 6, $this->t($row[0]), 1, 0, 'L');
            $this->Cell($w * 0.29, 6, $this->t($row[1]), 1, 0, 'L');
            $this->Cell($w * 0.29, 6, $this->t($row[2]), 1, 1, 'L');
        }
    }

    private function t(string $text): string
    {
        // FPDF core fonts: keep ASCII-safe text
        if (function_exists('iconv')) {
            $converted = @iconv('UTF-8', 'ISO-8859-1//TRANSLIT//IGNORE', $text);
            if ($converted !== false && $converted !== '') {
                return $converted;
            }
        }
        return preg_replace('/[^\x20-\x7E]/', '', $text) ?: $text;
    }
}
