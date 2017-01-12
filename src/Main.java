import org.jdom.Document;
import org.jdom.Element;
import org.jdom.input.SAXBuilder;

import java.io.File;
import java.util.Iterator;
import java.util.List;

/**
 * Created by lloison on 12/01/2017.
 */
public class Main {
    static Document document;
    static Element racine;

    //On parse le fichier et on initialise la racine de
    //notre arborescence
    static void lireFichier(String fichier) throws Exception
    {
        SAXBuilder sxb = new SAXBuilder();
        document = sxb.build(new File(fichier));
        racine = document.getRootElement();
    }

    public static void getListePays() {
        List listeAllCountry = racine.getChildren("country");
        Iterator i = listeAllCountry.iterator();
        System.out.println("<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"no\"?>\n" +
                "<!DOCTYPE liste-pays SYSTEM \"liste-pays.dtd\">");
        System.out.println("<liste-pays>");
        while(i.hasNext())
        {
            Element countryCourant = (Element)i.next();
            List listeencompassed = countryCourant.getChildren("encompassed");
            for (Object encompassed: listeencompassed) {
                Element encompassedElem = (Element) encompassed;
                if ("asia".equals(encompassedElem.getAttributeValue("continent")) && Integer.parseInt(encompassedElem.getAttributeValue("percentage")) < 100) {
                    String nomPays = countryCourant.getChild("name").getValue();
                    int percentageAsia = Integer.parseInt(encompassedElem.getAttributeValue("percentage"));
                    int percentageOther = (100 - percentageAsia);
                    String nomCapitale = "";
                    String idCapital = countryCourant.getAttributeValue("capital");
                    List listeCity = countryCourant.getChildren("city");
                    List listeProvince = countryCourant.getChildren("province");
                    for (Object city : listeCity) {
                        Element cityElem = (Element) city;
                        if(idCapital.equals(cityElem.getAttributeValue("id"))) {
                            nomCapitale = cityElem.getChild("name").getValue();
                        }
                    }
                    for (Object province : listeProvince) {
                        Element provinceElem = (Element) province;
                        List listeCityProvince = provinceElem.getChildren("city");
                        for (Object city : listeCityProvince) {
                            Element cityElem = (Element) city;
                            if(idCapital.equals(cityElem.getAttributeValue("id"))) {
                                nomCapitale = cityElem.getChild("name").getValue();
                            }
                        }
                    }
                    System.out.println("<pays nom=\"" + nomPays + "\" capitale=\"" + nomCapitale + "\" proportion-asie=\"" + percentageAsia + "\" proportion-autres=\"" + percentageOther + "\"></pays>");
                }
            }
        }
        System.out.println("</liste-pays>");
    }



    public static void main(String[] args)
    {
        try
        {
            //On crée un nouveau document JDOM avec en argument le fichier XML
            lireFichier("TP-XML/mondial.xml");
            getListePays();
        }
        catch(Exception e){}


    }
}
