import mongoose from "mongoose";
const { Schema } = mongoose;

const UserSchema = new Schema({
    username: String,
    password: String,
    created: { type: Date, default: new Date() },
});

export default mongoose.model("user", UserSchema);
