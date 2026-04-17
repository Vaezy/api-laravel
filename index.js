import bodyParser from "body-parser";
import express from "express";
import { initDb } from "./src/db/db.js";

import { authMdlr } from "./src/auth/auth.js";
import {
    createPokemon,
    deletePokemon,
    findAllPokemons,
    findPokemonByPk,
    updatePokemon,
} from "./src/routes/pokemon-route.js";

import { createFirstUser } from "./src/db/create-first-user.js";
import { userLogin } from "./src/routes/user-route.js";

const app = express();

app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: true }));

initDb();
createFirstUser();

app.post("/api/login", userLogin);

app.use(authMdlr);

app.get("/api/pokemons", findAllPokemons);
app.get("/api/pokemons/:id", findPokemonByPk);
app.post("/api/pokemons", createPokemon);
app.put("/api/pokemons/:id", updatePokemon);
app.delete("/api/pokemons/:id", deletePokemon);

app.use((req, res) => res.json({ message: "notfound" }));

app.listen(8000, () => {
    console.log("App listening on port 8000");
});
