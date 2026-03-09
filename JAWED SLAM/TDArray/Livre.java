
public class Livre {
	private String ISBN,titre, Auteur;
	private int prix;
	//Début des GET et SET
	public String getTitre() {
		return titre;
	}
	public void setTitre(String titre) {
		this.titre = titre;
	}
	public String getAuteur() {
		return Auteur;
	}
	public void setAuteur(String auteur) {
		Auteur = auteur;
	}
	public int getPrix() {
		return prix;
	}
	public void setPrix(int prix) {
		this.prix = prix;
	}
	//Constructeur du livre
	public Livre(String ISBN, String titre, String auteur, int prix) {
		this.ISBN=ISBN;
		this.titre = titre;
		Auteur = auteur;
		this.prix = prix;
		
	} 
	//Fonction d'affichage
	public void Afficher(){
		System.out.print("Titre: "+titre+",");
		System.out.print("Auteur: "+Auteur+",");
		System.out.print("Prix: "+prix+"€");
	}
	public String getISBN() {
		return ISBN;
	}
	public void setISBN(String iSBN) {
		ISBN = iSBN;
	}
}
