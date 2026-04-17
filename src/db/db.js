import mongoose from "mongoose";
import Pokemon from "../models/pokemon-model.js";
import pokemons from "./mock-pokemon.js";

export const initDb = () => {
  const connectionString = "mongodb://127.0.0.1:27017/pokedex";

  mongoose
    .connect(connectionString)
    .then(async () => {
      console.log("✅ Connexion à MongoDB réussie !");

      const count = await Pokemon.countDocuments();

      if (count === 0) {
        console.log("La base est vide, insertion des pokémons...");

        await Pokemon.insertMany(pokemons);

        console.log("✅ Pokémons insérés avec succès !");
      } else {
        console.log(`La base contient déjà ${count} pokémons.`);
      }
    })
    .catch((error) => {
      console.error("Erreur de connexion :", error);
    });
};
