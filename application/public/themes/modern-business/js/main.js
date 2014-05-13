 function load_page(url,id_contenidor){
    var xml = $.ajax({
         url: url,
         success: function(xml){
            $(id_contenidor).html("");
            load_rss(xml, id_contenidor);
   }
 });
}

function load_rss(xml, id_contenidor){
   var limit = xml.getElementsByTagName('item').length; //obtinc la quantitat d'entrades
   var rss = ""; //començo el string
   for(var l=1; l<=3; l++) { // un for desde 1 fins la quantitat de'entrades
    //obtinc titol vincle data de publicació i descripció
      var title = xml.getElementsByTagName('title').item(l+1).firstChild.data;
      var link = xml.getElementsByTagName('link').item(l+1).firstChild.data;
      var pubDate = xml.getElementsByTagName('pubDate').item(l- 1).firstChild.data;
      var description = xml.getElementsByTagName('description').item(l+1).firstChild.data;
      var date = pubDate.split("+",1);
      rss = "<titol><a href=\""+link+"\" target='_blank' style='font-weight: bold;'>"+title+"</a></titol><br/><descripcio>"+description+"</descripcio><hr />";
      $(id_contenidor).append(rss);
    }
}