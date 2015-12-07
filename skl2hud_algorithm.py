input seznam_skladeb
kandidati = vyber hudebniky majici nacvicenu alespon jednu skladbu koncertu()
kandidati = kandidati.order_by(pocet_ovladanych_nastroju, asc)
zvoleni_pro_skladbY = array(count(seznam_skladeb))
for skladba in seznam_skladeb do
  for nastroj in skladba do
    zvoleni_pro_skladbU = array()
    for k in kandidati do
      if k.ma_nastudovano(skladba) and k.hraje_na(nastroj)
        zvoleni_pro_skladbU.push(k.pop())   //odebere z kandidatu a presune do zvolenych pro skladbu
      if k.empty()
        raise error
    done
    zvoleni_pro_skladbY.push(zvoleni_pro_skladbU)
  done
done
seznam_hudebniku = call_user_func_array('array_merge', zvoleni_pro_skladbY)
seznam_hudebniku = array_unique(seznam_hudebniku)
output seznam_hudebniku