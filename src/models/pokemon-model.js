import mongoose from "mongoose";
const { Schema } = mongoose;

const PokemonSchema = new Schema({
    name: String,
    hp: Number,
    cp: Number,
    picture: String,
    types: [String],
    created: { type: Date, default: new Date() },
});

export default mongoose.model("pokemon", PokemonSchema);
