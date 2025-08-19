<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Certificate of Appreciation</title>
    <style>
        @media print {
            @page {
                size: A4 landscape;
                margin: 5.5mm;
            }

            body {
                margin: 0;
                padding: 0;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;

            }

            .no-print {
                display: none !important;
            }

            #print_watermark {
                background-image: url('<?= base_url("/public/assets/img/logo/1.png"); ?>') !important;
                background-repeat: no-repeat !important;
                background-position: center !important;
            }

            .certificate {
                background: linear-gradient(to right, #d0f0d012, #66e5117d) !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }

        body {
            font-family: "Times New Roman", serif;
            margin: 0;
            padding: 0;
            background: #fff;
        }

        .certificate {
            position: relative;
            width: 100%;
            height: 100%;
            max-width: 100vw;
            background: linear-gradient(to right, #d0f0d012, #66e5117d);
            padding: 20px 40px;
            box-sizing: border-box;
            text-align: center;
            border: 6px double rgb(21, 22, 21);
        }

        .header-logo {
            width: 80px;
            position: absolute;
            top: 30px;
            left: 40px;
        }

        .gold-medal {
            width: 80px;
            position: absolute;
            top: 30px;
            right: 40px;
        }

        .title {
            font-weight: bold;
            font-size: 18px;
            text-transform: uppercase;
        }

        h1 {
            font-size: 20px;
            margin: 20px 0 10px;
            text-decoration: underline;
        }

        .content {
            font-size: 16px;
            line-height: 1.6;
            text-align: left;
            padding: 0 10px;
        }

        .content strong {
            color: #000;
        }

        .check {
            margin-left: 20px;
        }

        .footer {
            font-style: italic;
            font-size: 14px;
            color: #2c662d;
            text-align: center;
            margin-top: 10px;
        }

        .signature p {
            margin: 0;
            font-size: 14px;
        }

        .note {
            margin-top: 5px;
            font-size: 13px;
            color: #666;
        }

        /* #print_watermark {
            background-color: #FFFFFF;
            background-image: url('<?= base_url("/public/assets/img/logo1.png"); ?>');
            background-repeat: no-repeat;
            background-position: center;
            background-size: 250px auto;
        } */

        .bottom-section {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin: 20px 10px 0;
        }

        .qr img {
            width: 90px;
            height: auto;
        }

        .signature {
            text-align: right;
        }

        /* Show print button on hover */
        .certificate:hover .no-print {
            display: flex;
        }

        .no-print {
            display: none;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 999;
        }

        .no-print button {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #0b6623;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 8px;
        }

        .no-print button:hover {
            background-color: #0a5a1d;
        }
        .watermark-img {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 250px;
            opacity: 0.1;
            transform: translate(-50%, -50%);
            z-index: 0;
            pointer-events: none;
        }

    </style>
</head>

<body id="print_watermark">

    <div class="certificate" id="printArea">
    <img src="<?= base_url("/public/assets/img/logo1.png"); ?>" 
       alt="Watermark"
       class="watermark-img">

        <img class="header-logo" src="<?= base_url("public/assets/img/logo1.png"); ?>" alt="Jharkhand Municipal Logo">
        <img class="gold-medal" src="<?= base_url("public/assets/img/medal/$type.png"); ?>" alt="Gold Medal">

        <div class="title">Government of Jharkhand</div>
        <div class="title">Urban Development And Housing Department (UD&HD)</div>
        <div class="title">Ranchi Municipal Corporation</div>

        <h1>Certificate of Appreciation</h1>

        <p>This Certificate is Proudly Presented To</p>

        <div class="content">
            <strong>Name :</strong> <?= $ownerName ?? ""; ?><br>
            <strong>Holding No. :</strong> <?= $holdingNo ?? ""; ?><br><br>

            In recognition of their responsible and timely payment of <strong>Property Tax</strong> for the financial year <strong><?= $fyear ?? ""; ?></strong>, thereby contributing to the development and betterment of their city.<br><br>

            <strong><?= $type ?? ""; ?> Category</strong><br>

            <div class="check">✓ Full payment of property tax by <strong><?= $qtr ?? ""; ?><?=countingNumber($qtr)?> Quarter</strong></div>
            <div class="check">✓ Mode of payment: <strong><?= $paymentMode ?? ""; ?></strong></div><br>

            We sincerely appreciate your civic responsibility and contribution towards building a more transparent, efficient, and accountable urban governance system.<br><br>

            <strong>Date of Issue:</strong> <?= $issuDate ?? ""; ?><br>
            <strong>Certificate ID:</strong> <?= $certificatId ?? ""; ?>
        </div>

        <div class="bottom-section">
            <div class="qr">
                <img src="<?= base_url('writable/uploads/qrCodeGenerator/' . $quarCode) ?>" alt="QR Code">
            </div>

            <div class="signature">
                <img style="margin-top:-145px;" src="<?= base_url('writable/eo_sign/gautam.png') ?>" alt="signature">
                <p> Warm Regards</p>
                <p><strong>Sd /-</strong></p>
                <p>Municipal Commissioner / Executive Officer / Administrator</p>
                <p>Ranchi Municipal Corporation</p>
                
            </div>
        </div>

        <div class="footer">
            "Your timely tax payment is a building block for a stronger city. Thank you for being a responsible citizen!"<br>
            <div class="note">** This is a digitally signed certificate. No physical Signature is required.</div>
        </div>

        <!-- Print Button appears on hover -->
        <div class="no-print">
            <button onclick="printCertificate()">Print Certificate</button>
        </div>
    </div>

    <script>
        function printCertificate() {
            const printButton = document.querySelector('.no-print');

            if (printButton) {
                printButton.style.display = 'none';
            }

            setTimeout(() => {
                window.print();
            }, 100);

            window.onafterprint = function() {
                setTimeout(() => {
                    window.location.reload();
                }, 200);
            };
        }
    </script>

</body>

</html>