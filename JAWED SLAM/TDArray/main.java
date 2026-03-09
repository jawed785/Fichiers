
public class main {

	public static void main(String[] args) {
		// TODO Auto-generated method stub
		Livre livre1,livre2;
		int prix;
		String Titre, Auteur;
		livre1 = new Livre("1111111","titre 1", "auteur 1", 1);
		livre2 = new Livre("2222222","titre 2", "auteur 2", 2);
		livre1.Afficher();
		System.out.println("");
		livre2.Afficher();
	}

}
