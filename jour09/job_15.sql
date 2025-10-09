SELECT salles.nom as "Salle", etages.nom as "Etage" FROM salles
LEFT JOIN etages ON etages.id = id_etage;