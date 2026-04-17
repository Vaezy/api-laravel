import bcrypt from "bcrypt";
import User from "../models/user-model.js";

export const createFirstUser = async () => {
    const hash = await bcrypt.hash("pikachu", 10);

    try {
        const user = await User.create({
            username: "pikachu",
            password: hash,
        });
        console.log(`La création du premier utilisateur est OK : ${user}`);
    } catch (error) {
        console.log(
            `Note : L'utilisateur n'a pas été créé (il existe peut-être déjà).`,
        );
    }
};
