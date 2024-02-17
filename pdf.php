<?php
require_once('fpdf/fpdf.php');
require_once('fpdi/src/autoload.php');

function createPdfFromText($text, $pdfFile, $outputPdfFile) {
    // استخدام FPDI لتحميل الملف القائم
    $pdf = new \setasign\Fpdi\Fpdi();

    // إنشاء ملف PDF جديد
    $pdf->AddPage();
    $pdf->setSourceFile($pdfFile);
    $tplId = $pdf->importPage(1);
    $pdf->useTemplate($tplId); 

    // تحديد حجم النص ونوعه
    $fontSize = 10;
    $fontName = 'Arial';
    $fontStyle = 'B'; // Bold

    // ضبط النص
    $pdf->SetFont($fontName, $fontStyle, $fontSize);
    $pdf->SetTextColor(0, 108, 62); // اللون #006C3E بتنسيق RGB

    // حساب عرض النص وموضعه لوضعه في المنتصف
    $textWidth = $pdf->GetStringWidth($text);
    $pageWidth = $pdf->GetPageWidth(); // عرض الصفحة بوحدات ملم
    $x = ($pageWidth - $textWidth) / 6; // النص في المنتصف بالأفقي
    $y = 26; // النص في الجزء العلوي للصفحة بالرأسي
    
    // إضافة النص إلى الملف
    $pdf->SetXY($x, $y);
    $pdf->Cell(0, 10, $text, 0, 1, 'C');

    // حفظ الملف PDF الناتج
    $pdf->Output($outputPdfFile, 'F');
}

// قراءة الملف النصي وإنشاء ملف PDF لكل سطر
$lines = file('input.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$pdfFile = 'existing.pdf'; // اسم ملف PDF القائم
foreach ($lines as $line) {
    // توليد اسم ملف PDF جديد بناءً على السطر الحالي
    $outputPdfFile = 'output_' . preg_replace('/\s+/', '_', $line) . '.pdf';
    
    // إنشاء ملف PDF للسطر الحالي
    createPdfFromText($line, $pdfFile, $outputPdfFile);
}
?>
