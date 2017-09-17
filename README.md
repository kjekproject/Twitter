# Twitter

Aplikacja w stylu Twittera umożliwia przeglądanie i dodawanie postów oraz komentarzy, ponadto użytkownicy mogą komunikować się poprzez wysyłanie wiadomości.

Model danych:

Użytkownik:
- adres email,
- nazwa użytkownika,
- hasło.

Tweet:
- treść,
- data dodania,
- id użytkownika (relacja z użytkownikiem).

Komentarz:
- treść,
- data dodania,
- id użytkownika (relacja z użytkownikiem),
- id tweeta (relacja z tweetem).

Wiadomość:
- id autora (relacja z użytkownikiem),
- id adresata (relacja z użytkownikiem),
- treść,
- data dodania,
- status.

Widoki:
- strona rejestracji,
- strona logowania,
- strona główna,
- strona z tweetami zalogowanego użytkownika,
- strona z wiadomościami,
- strona edycji użytkownika,
- strona pojedyńczego tweeta,
- strona z tweetami danego użytkownika,
- strona wysyłania wiadomości,
- strona wyświetlania pojedyńczej wiadomości.

Funkcjonalności:
- rejestracja,
- logowanie użytkownika,
- dodawanie tweetów,
- dodawanie komentarzy,
- wysyłanie wiadomości,
- edycja danych użytkownika.

Przykładowe widoki:

Strona rejestracji
![obraz](https://user-images.githubusercontent.com/22776880/30523056-99aa3cc0-9bda-11e7-9e7c-8262e0c2a0fa.png)

Strona główna:
![obraz](https://user-images.githubusercontent.com/22776880/30523074-ea597a14-9bda-11e7-8382-76a5d8e85be7.png)

