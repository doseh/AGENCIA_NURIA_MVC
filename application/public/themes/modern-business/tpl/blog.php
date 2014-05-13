<?php
    /**
     * Conté la ruta del rss a mostrar
     *
     * @author Ferran <feerraan@gmail.com>
     */
   $filename = "http://elpais.com/tag/rss/meteorologia/a/";
   header("Content-type:text/xml");
   readfile ($filename);
?>