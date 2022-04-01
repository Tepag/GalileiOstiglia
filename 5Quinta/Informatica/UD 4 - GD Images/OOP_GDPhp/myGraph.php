<?php
/**
 * Classe sviluppata per la realizzazione di un grafico a linea spezata stile "Excel"
 */
class myGraph{
    private $totalWidth;
    private $totalHeight;
    private $leftMargin;
    private $topMargin;
    private $rightMargin;
    private $bottomMargin;
    private $graphWidth;
    private $graphHeight;

    private $im;
    private $colors;
    private $values = array();
    /**
     * Costruttore della classe
     * @param int $w Larghezza in pixel dell'immagine.
     * @param int $h Altezza in pixel dell'immagine.
     */
    function __construct($w, $h){
        $this->totalWidth = $w;
        $this->totalHeight = $h;

        $this->SetInternalMargins( 10, 10, 10, 10);

        $this->im = imageCreate($this->totalWidth, $this->totalHeight);
        $this->colors = array(
            "white" => imageColorAllocate($this->im, 255,255,255),
            "black" => imageColorAllocate($this->im, 0,0,0),
            "blue" => imageColorAllocate($this->im, 0,0,255),
        );
    }    
    /**
     * Funzione per modificare i margini interni di default.
     * @param int $lm Pixel margine sinistro.
     * @param int $tm Pixel margine superiore.
     * @param int $rm Pixel margine destro.
     * @param int $bm Pixel margine inferiore.
     */
    public function SetInternalMargins($lm, $tm, $rm, $bm){
        $this->leftMargin = $lm;
        $this->topMargin = $tm;
        $this->rightMargin = $rm;
        $this->bottomMargin = $bm;

        $this->graphWidth = $this->totalWidth - $this->leftMargin - $this->rightMargin;
        $this->graphHeight = $this->totalHeight - $this->topMargin - $this->bottomMargin;
    }
    /**
     * Disegna un rettangolo per delimitare i confini dell'immagine.
     */
    public function DrawBounds(){
        imageRectangle($this->im, 0, 0, $this->totalWidth-1, $this->totalHeight-1, $this->colors['black']);
    }
    /**
     * Disegna un rettangolo per delimitare i confini dell'area del grafico.
     */
    public function DrawInternalBounds(){
        imageRectangle($this->im, $this->leftMargin, $this->topMargin, $this->leftMargin+$this->graphWidth, $this->topMargin+$this->graphHeight, $this->colors['blue']);
    }

    public function DrawPoint($val, $maxVal){
        // calcolo i pixel in proporzione
        // $maxVal:$graphHeight = $val:$pixel --> $pixel= $val*$graphHeight/$maxVal
        $pixel = $val * $this->graphHeight / $maxVal;
        $yPunto = $this->totalHeight - $this->bottomMargin - $pixel;
        $xPunto = $this->leftMargin + ($this->graphWidth/2);

        imageFilledEllipse($this->im, $xPunto, $yPunto, 10, 10, $this->colors['blue']);
    }

    public function DrawPoints(){
        $maxVal = $this->values[0];
        for($i=1; $i<count($this->values); $i++)
            if($this->values[$i] > $maxVal) $maxVal = $this->values[$i];
        
        $deltaX = $this->graphWidth / (count($this->values)-1);
        $xPunto = $this->leftMargin;
        for($i=0; $i<count($this->values); $i++){
            $pixel = $this->values[$i] * $this->graphHeight / $maxVal;
            //echo($this->values[$i]." ".$pixel."<br >");
            $yPunto = $this->totalHeight - $this->bottomMargin - $pixel;
            imageFilledEllipse($this->im, $xPunto, $yPunto, 10, 10, $this->colors['blue']);

            $xPunto = $xPunto + $deltaX;
        }        
    }

    public function SetValues($valori){
        for($i=0; $i<count($valori); $i++){
            array_push($this->values, $valori[$i]);
        }
    }

    /**
     * Output del codice PNG realtivo all'immagine creata.
     */
    public function Output(){
        //header("Content-type: image/png");
        imagePNG($this->im);
        imageDestroy($this->im);
    }   
}
?>