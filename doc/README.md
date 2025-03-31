# 📖 Documentació del projecte MoviieTick

## 🎯 Objectius
*MoviieTick* és una aplicació web per a la gestió de reserves d’entrades de cinema. L’objectiu principal és permetre als usuaris seleccionar pel·lícules, escollir seients i realitzar el pagament de manera segura. Un cop confirmada la compra, es genera un **PDF amb un codi QR** per validar l’entrada.

## 🏗 Arquitectura bàsica

### 🛠 Tecnologies utilitzades
- **Frontend:** Vue 3, Tailwind CSS
- **Backend:** Laravel (PHP), MySQL
- **Autenticació:** Laravel Sanctum
- **Generació de PDF:** jsPDF

### 🔗 Interrelació entre components
1. **Frontend (Vue.js):** Interfície d'usuari per seleccionar pel·lícules i seients.
2. **Backend (Laravel):** Gestió de sessions de cinema, reserves i generació del PDF amb el codi QR.
3. **Base de dades (MySQL):** Emmagatzema informació de pel·lícules, sessions, seients i reserves.
4. **Servidor:** Serveix l'aplicació i gestiona peticions API.

## 💻 Configuració de l'entorn de desenvolupament

1. **Clonar el repositori:**  
   ```sh
   git clone https://github.com/inspedralbes/tr3-cinema-24-25-EduardV1.git
   cd front
   ```
2. **Instal·lar dependències del backend:**
   ```sh
   cd back
   composer install
   cp .env.example .env
   php artisan key:generate
   ```
3. **Configurar la base de dades:**
   - Editar `.env` i configurar `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`.
   - Executar:
     ```sh
     php artisan migrate --seed
     ```
4. **Instal·lar dependències del frontend:**
   ```sh
   cd ../front
   npm install
   ```
5. **Executar en desenvolupament:**
   - Backend: `php artisan serve`
   - Frontend: `npm run dev`

## 🚀 Desplegament a producció

1. **Backend:**
   - Pujar els fitxers Laravel a un servidor Apache.
   - Configurar la connexió a la base de dades.
   - Executar `php artisan migrate --seed`.

2. **Frontend:**
   - Generar build de producció: `npm run build i npm run generate`
   - Pujar els fitxers generats al servidor.

3. **Base de dades:**
   - Exportar i importar en un servidor MySQL en producció.

## 📌 Endpoints de l’API

### 🎥 Pel·lícules
- **GET** `/api/movies` → Llista de pel·lícules disponibles.
- **GET** `/api/movies/{id}` → Detall d’una pel·lícula.

### 🎟️ Sessions i seients
- **GET** `/api/sessions` → Llista de sessions.
- **GET** `/api/sessions/{id}/seats` → Llista de seients d'una sessió.
- **POST** `/api/sessions/{id}/reserve` → Reservar seients.
  - **Exemple JSON de petició:**
    ```json
    {
      "seats": ["A1", "A2"],
      "user": {
        "name": "Joan",
        "email": "joan@example.com"
      }
    }
    ```
  - **Resposta:** `201 Created`

### 📩 Generació de PDF amb codi QR
- **GET** `/api/tickets/{id}/download` → Descarregar l’entrada en PDF.

## 🔍 Altres elements importants
- Gestió d’autenticació amb Laravel Sanctum.
- Validacions en backend per evitar duplicitat de reserves.
- Disseny responsiu per a dispositius mòbils.
