document.addEventListener('DOMContentLoaded', function() {
  const carousel = document.querySelector('.carousel-container');
  const prevBtn = document.querySelector('.prev');
  const nextBtn = document.querySelector('.next');
  
  nextBtn.addEventListener('click', () => {
      carousel.scrollBy({ left: 300, behavior: 'smooth' });
  });
  
  prevBtn.addEventListener('click', () => {
      carousel.scrollBy({ left: -300, behavior: 'smooth' });
  });
});


        
  
  
  function showTab(tabName) {
            // Masquer tous les contenus
            document.querySelectorAll('.auth-content').forEach(content => {
                content.classList.remove('active');
            });
            
            // Désactiver tous les onglets
            document.querySelectorAll('.auth-tab').forEach(tab => {
                tab.classList.remove('active');
            });
            
            // Afficher le contenu sélectionné
            document.getElementById(tabName + '-tab').classList.add('active');
            
            // Activer l'onglet sélectionné
            event.currentTarget.classList.add('active');
        }

       
      
    

