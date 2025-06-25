import jwt  
from datetime import datetime
import os

class TokenSystem:
    def __init__(self):
        self.load_keys()
        self.FLAG = open("/chal/flag.txt", "r").read()

    def load_keys(self):
        try:
            with open('./private.pem', 'rb') as f:
                self.PRIVATE_KEY = f.read()
            with open('./public.pem', 'rb') as f:
                self.PUBLIC_KEY = f.read()
        except FileNotFoundError as e:
            print(f"Erreur: Fichier de cle non trouve - {e}")
            raise

    def print_banner(self):
        print("SYSTEME D'AUTHENTIFICATION JAVIS 2.0")

    def print_menu(self):
        print("MENU PRINCIPAL")
        print("[1] Generer session utilisateur")
        print("[2] Valider token d'authentification")
        print("[3] Deconnexion")

    def authorise(self, token):
        try:
            print("Analyse du token en cours...")
            decoded = jwt.decode(token, self.PUBLIC_KEY, algorithms=['HS256', 'RS256'])
            print("Token valide avec succes")
        except jwt.InvalidTokenError as e:
            return {
                "status": "error",
                "message": f"Token invalide : {str(e)}",
                "timestamp": datetime.now().isoformat()
            }

        if "admin" in decoded and decoded.get("admin"):
            return {
                "status": "success",
                "level": "admin",
                "message": "Acces administrateur accorde",
                "flag": self.FLAG,
                "timestamp": datetime.now().isoformat()
            }
        elif "username" in decoded:
            return {
                "status": "success",
                "level": "user",
                "message": f"Bienvenue {decoded['username']}",
                "username": decoded['username'],
                "timestamp": datetime.now().isoformat()
            }
        else:
            return {
                "status": "error",
                "message": "Session invalide ou incomplete",
                "timestamp": datetime.now().isoformat()
            }

    def create_session(self, username):
        print(f"Generation de session pour: {username}")
        payload = {
            'username': username,
            'admin': False,
            'created_at': datetime.now().isoformat(),
            'session_id': f"sess_{hash(username + str(datetime.now()))}"
        }
        token = jwt.encode(payload, self.PRIVATE_KEY, algorithm='RS256')
        return {
            "status": "success",
            "session": token,
            "username": username,
            "created_at": payload['created_at']
        }


    def display_result(self, result):
        print("\n--- ReSULTAT ---")
        if result.get("status") == "success":
            print("Statut: SUCCeS")
            if result.get("level") == "admin":
                print("Acces: ADMINISTRATEUR")
                print(f"FLAG: {result.get('flag')}")
            elif result.get("level") == "user":
                print(f"Utilisateur: {result.get('username')}")
            if result.get("session"):
                print("\nToken genere:")
                print(result["session"])
        elif result.get("status") == "error":
            print(f"Erreur: {result.get('message')}")
        print("----------------\n")

    def run(self):
        self.print_banner()
        while True:
            self.print_menu()
            choix = input("\nJ.A.V.I.S > ").strip()
            if choix == "1":
                print("\nGeNeRATION DE SESSION")
                username = input("Nom d'utilisateur: ").strip()
                if username:
                    result = self.create_session(username)
                    self.display_result(result)
                else:
                    print("Nom d'utilisateur requis")
            elif choix == "2":
                print("\nVALIDATION DE TOKEN")
                token = input("Token: ").strip()
                if token:
                    result = self.authorise(token)
                    self.display_result(result)
                else:
                    print("Token requis")
            elif choix == "3":
                print("Session terminee. Au revoir !")
                exit()
            else:
                print("Commande non reconnue. Veuillez selectionner 1 à 4.")
            input("\nAppuyez sur Entree pour continuer...")
            os.system('cls' if os.name == 'nt' else 'clear')

def main():
    try:
        system = TokenSystem()
        system.run()
    except KeyboardInterrupt:
        print("\nInterruption detectee. Arret du systeme.")
    except Exception as e:
        print(f"Erreur systeme: {e}")

if __name__ == "__main__":
    main()
