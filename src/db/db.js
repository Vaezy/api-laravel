import mongoose from "mongoose";

export const initDb = () => {
    mongoose
        .connect("mongodb://localhost/pokemon-api-rest")
        .then(() => console.log("✅ Connexion à MongoDB réussie !"))
        .catch((err) => console.error("❌ Erreur de connexion :", err));
};
