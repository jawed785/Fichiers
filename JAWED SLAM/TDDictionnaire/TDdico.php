<?php 

public class LIVRE {

    
public class Livre {
	private String $ISBN;
	private String $Titre;
    private int $Prix;
    private int $Dispo;

    //constructeur
    public function__constructeur($unISBN,$unTitre,$unPrix,$unDispo){
        $this->ISBN=$unISBN;
        $this->Titre=$unTitre;
        $this->Prix=$unPrix;
        $this->Dispo=$unDispo;
    }

	//Début des GET et SET
	public function getISBN() {
		return $this->Titre;
	}
	public function setISBN() {
		$this->ISBN = $ISBN;
	}
	public function getTitre() {
		return $this->Titre;
	}
	public function setTitre() {
		$this->Titre = $Titre;
	}
	public function getPrix() {
		return $this->Titre;
	}
	public function setPrix() {
		$this->Prix = $Prix;
	}
    public function getDispo() {
		return $this->Titre;
	}
	public function setDispo() {
		$this->Dispo = $Dispo;
	}
	//Construire le livre
	$livre1 = new LIVRE ("EEE032","pragrammer en C",10,1);
    $livre2 = new LIVRE ("JAV44","pragrammer en JAVA",50,1);

    //CREATION D'UN DICTIONNAIRE
    $monDico= array();
    $monDico[$Livre1->getISBN()] = $Livre1;
    $monDico[$Livre2->getISBN()] = $Livre2;
    echo $monDico[$Livre1->getISBN()];


}

}

?>