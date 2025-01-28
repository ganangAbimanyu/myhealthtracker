// Fetch and display health records
function fetchRecords(userId) {
    fetch(`fetch_records.php?user_id=${userId}`)
        .then(response => response.json())
        .then(data => {
            const recordsDiv = document.getElementById('records');
            recordsDiv.innerHTML = ''; // Clear previous records

            if (data.length === 0) {
                recordsDiv.innerHTML = "<p>No records found.</p>";
            } else {
                data.forEach(record => {
                    recordsDiv.innerHTML += `
                        <div class="record">
                            <h3>${record.type}</h3>
                            <p>${record.description}</p>
                            <p>${record.date} at ${record.time}</p>
                            <hr>
                        </div>
                    `;
                });
            }
        })
        .catch(error => console.error('Error fetching records:', error));
}

// Fetch and display nutrition information
function fetchNutritionInfo(query) {
    fetch('nutritionix.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ query: query })
    })
        .then(response => response.json())
        .then(data => {
            const resultsDiv = document.getElementById('nutritionResults');
            resultsDiv.innerHTML = ''; // Clear previous results

            if (data.foods && data.foods.length > 0) {
                data.foods.forEach(food => {
                    resultsDiv.innerHTML += `
                        <div class="nutrition-item">
                            <h3>${food.food_name}</h3>
                            <p>Calories: ${food.nf_calories} kcal</p>
                            <p>Protein: ${food.nf_protein} g</p>
                            <p>Carbs: ${food.nf_total_carbohydrate} g</p>
                            <p>Fat: ${food.nf_total_fat} g</p>
                            <hr>
                        </div>
                    `;
                });
            } else {
                resultsDiv.innerHTML = '<p>No nutrition data found.</p>';
            }
        })
        .catch(error => console.error('Error fetching nutrition info:', error));
}

// Add event listeners for specific pages
document.addEventListener('DOMContentLoaded', () => {
    // Fetch records on the View Records page
    if (document.getElementById('records')) {
        const userId = 1; // Replace with the actual logged-in user's ID
        fetchRecords(userId);
    }

    // Handle Nutritionix form submission
    const nutritionForm = document.getElementById('nutritionixForm');
    if (nutritionForm) {
        nutritionForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const query = document.getElementById('foodInput').value;
            fetchNutritionInfo(query);
        });
    }
});
