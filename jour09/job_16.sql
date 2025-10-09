SELECT salles.nom as "Biggest Room" , etages.nom as "Etage" FROM salles
LEFT JOIN etages ON etages.id = id_etage
WHERE capacite = (SELECT MAX(capacite) FROM salles);
-- OU sans WHERE ORDER BY capacite DESC LIMIT 1;