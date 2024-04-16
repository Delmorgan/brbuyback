import requests
from flask import Flask, render_template

app = Flask(__name__)

# EVE Online ESI base URL
base_url = "https://esi.evetech.net/latest"

# Your application's client ID and secret key
client_id = "YOUR_CLIENT_ID"
client_secret = "YOUR_CLIENT_SECRET"

# Route to fetch materials in ship hangar
@app.route('/materials')
def get_materials():
    # Make a request to authenticate and get access token
    auth_url = "https://login.eveonline.com/v2/oauth/token"
    auth_data = {
        "grant_type": "client_credentials",
        "client_id": client_id,
        "client_secret": client_secret
    }
    auth_response = requests.post(auth_url, data=auth_data)
    auth_response.raise_for_status()
    access_token = auth_response.json()["access_token"]

    # Make a request to get materials in ship hangar
    hangar_url = f"{base_url}/characters/character_id/ship"
    headers = {"Authorization": f"Bearer {access_token}"}
    hangar_response = requests.get(hangar_url, headers=headers)
    hangar_response.raise_for_status()

    # Process the response and extract materials data
    materials = hangar_response.json()

    # Pass materials data to a template and render it
    return render_template('materials.html', materials=materials)

if __name__ == '__main__':
    app.run(debug=True)
