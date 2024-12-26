import {useEffect, useState} from "react";
import ProductItem from "./ProductItem";
import BEConnectionService from "../services/BEConnectionService.js";

export default function Products() {
  const [loadedProducts, setLoadedProducts] = useState([]); //am anfang ist das Array noch leer da die Produkte noch nicht geladen werden

  useEffect(() => {
    async function fetchProducts() {
      try {
        const products = await BEConnectionService.getInstance().getAllArticles();
        setLoadedProducts(products);
      } catch (error) {
        console.error(error); // Füge Fehlerprotokollierung hinzu
      }
    }

    fetchProducts();  // fetchMeals nur innerhalb von useEffect aufrufen
  }, []);


  return (
    <ul id="products">
      {loadedProducts.map((p) => (
        <ProductItem key={p.id} product={p}/>
      ))}
    </ul>
  );
}

/**========================================================================
 * *    	                           INFO
 //? Hooks sind spezielle Funktionen idiesem Fall von React selbst

 *   useState:
 (Zustandsvariable) ist ein Speicherplatz, der Daten enthält, die sich
 ändern können. Und React ermöglicht es diese Daten automatisch zu verwalten und Darzustellen.

 *   loadedMeals: Dies ist die Variable, die den aktuellen Wert des "State" speichert. In
 *   setLoadedMeals: ist die Funktion, mit der die var(loadedMeals) geändert werden kann.

 *   Beispielhafter Ablauf:
 1. Beim ersten Rendern der Komponente ist loadedMeals leer ([]).
 2. Nachdem die Daten geladen wurden (z.B. in fetchMeals()), rufst du setLoadedMeals(meals) auf.
 3. Dadurch ändert sich loadedMeals und speichert die vom Server abgerufenen Mahlzeiten (z.B. eine Liste
 von Objekten wie { id: '1', name: 'Pizza', price: 10.99 }).
 4. Da sich der "State" geändert hat, wird die Komponente neu gerendert und zeigt die neuen Daten an.

 *   useEffect:
 - ist ein "side effect" (das Abrufen von Daten von einem Server)
 - stellt sicher, dass der Code für das Laden der Daten nur einmal ausgeführt wird, nämlich beim ersten Rendern der Komponente.

 *     async:
 - ist eine spezielle Funktion, die etwas tut, was länger dauert(das laden von Daten)
 - dadurch muss das Programm nicht angehalten werden.
 - mit await wird auf das Laden der Daten gewartet.
 -

 *========================================================================**/
