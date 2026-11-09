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
    getIngredients: () => requestAPI('/ingredients'),
    getRecipes: () => requestAPI(`/recipes`),
    getRecipeById: (id) => requestAPI(`/recipes/${id}`),
    getTagForRecipe: () => requestAPI(),
    getIngredientForRecipe: () => requestAPI(),
    createRecipe: (data) =>
        requestAPI('/recipes', { method: 'POST', body: JSON.stringify(data) }),
    updateRecipe: (id, data) =>
        requestAPI(`/recipes/${id}`, { method: 'PUT', body: JSON.stringify(data) }),
    deleteRecipe: (id) =>
        requestAPI(`/recipes/${id}`, { method: 'DELETE' }),
};