import Pokemon from "../models/pokemon-model.js";

export const findAllPokemons = async (req, res) => {
    const pokemons = await Pokemon.find();
    res.json({ message: pokemons });
};

export const findPokemonByPk = async (req, res) => {
    const pokemon = await Pokemon.findOne({ _id: req.params.id });
    res.json({ message: pokemon });
};

export const createPokemon = async (req, res) => {
    const pokemon = await Pokemon.create(req.body);
    res.json({ "new pokemon": pokemon });
};

export const updatePokemon = async (req, res) => {
    const id = req.params.id;
    const pokemon = await Pokemon.findOneAndUpdate({ _id: id }, req.body, {
        new: true,
    });
    res.json({ "pokemon maj : ": pokemon });
};

export const deletePokemon = async (req, res) => {
    let id = req.params.id;
    const pokemon = await Pokemon.findById({ _id: id });
    await Pokemon.deleteOne({ _id: id });
    res.json({ "this pokemon is deleted": pokemon });
};
