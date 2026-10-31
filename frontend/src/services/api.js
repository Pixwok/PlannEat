const API_URL = "/api";

async function requestAPI(path, options = {}) {
    const response = await fetch(`${API_URL}${path}`, {
        headers: {'Content-Type': 'application/json'}, 
        ...options,
    });
    if (!response.ok) {
        throw new Error(`Erreur API (${response.status})`);
    }
    return response.json();
}

// Fonction api autoriser
export const api = {
    getRecipes: () => requestAPI(`/recipes`),
    getRecipeById: () => requestAPI(`/recipes/${id}`),
    createRecipe: (data) =>
        request('/recipes', { method: 'POST', body: JSON.stringify(data) }),
    updateRecipe: (id, data) =>
        request(`/recipes/${id}`, { method: 'PUT', body: JSON.stringify(data) }),
    deleteRecipe: (id) =>
        request(`/recipes/${id}`, { method: 'DELETE' }),
};