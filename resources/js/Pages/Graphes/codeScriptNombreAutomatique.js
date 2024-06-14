function getRandomInt(min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
}

const users = [1, 2, 3, 4];
const products = [1, 2, 3, 4, 5, 6, 8, 9, 10, 11, 12, 14, 15];
const actions = ['vente'];
const startDate = new Date('2023-01-01');
const endDate = new Date('2024-06-30');
const insertions = [];
const totalInsertions = 500;

for (let i = 0; i < totalInsertions; i++) {
    const user_id = users[getRandomInt(0, users.length - 1)];
    const produit_id = products[getRandomInt(0, products.length - 1)];
    const action = actions[0];
    const quantite = getRandomInt(1, 20);

    const created_at = new Date(startDate.getTime() + Math.random() * (endDate.getTime() - startDate.getTime()));
    const updated_at = created_at;

    const formattedCreatedAt = `${created_at.getFullYear()}-${String(created_at.getMonth() + 1).padStart(2, '0')}-${String(created_at.getDate()).padStart(2, '0')} ${String(created_at.getHours()).padStart(2, '0')}:${String(created_at.getMinutes()).padStart(2, '0')}:${String(created_at.getSeconds()).padStart(2, '0')}`;
    const formattedUpdatedAt = formattedCreatedAt;

    const insertion = `INSERT INTO utilisateur_has_produits (user_id, produit_id, action, quantite, created_at, updated_at) VALUES (${user_id}, ${produit_id}, '${action}', ${quantite}, '${formattedCreatedAt}', '${formattedUpdatedAt}');`;

    insertions.push(insertion);
}

console.log(insertions.join('\n'));