<style>
    body {
        width: 100% !important;
        height: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
        background-color: #FAFAFA !important;
    }
    * {
        box-sizing: border-box !important;
        -moz-box-sizing: border-box !important;
    }
    .page {
        width: 210mm !important;
        min-height: 297mm !important;
        padding: 10mm !important;
        margin: 10mm auto !important;
        border: 1px #D3D3D3 solid !important;
        border-radius: 5px !important;
        background: white !important;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1) !important;
    }
    .subpage {
        padding: 0cm !important;
        border: 0px white solid !important;
        height: 257mm !important;
        outline: 0cm #FFEAEA solid !important;
    }
    
    @page {
        size: A4 !important;
        margin: 0 !important;
    }
    @media print {
        html, body {
            width: 210mm !important;
            height: 297mm !important;        
        }
        .page {
            margin: 0 !important;
            border: initial !important;
            border-radius: initial !important;
            width: initial !important;
            min-height: initial !important;
            box-shadow: initial !important;
            background: initial !important;
            page-break-after: always !important;
        }
    }
    
    #navbar, #bootcards-cards{
    	display: none;
    }
    #list {
    	width: 100% !important;
    }
    
    </style>
<div class="book">
    <div class="page">
        <div class="subpage" id="Substantive">
        
        <?php $colors="light"; include 'qs.php' ?>
        
        </div>    
    </div>
    <div class="page">
        <div class="subpage" id="Topical">Page 2/2</div>    
    </div>
</div>

<script>
// window.print();
</script>