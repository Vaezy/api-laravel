import bcrypt from "bcrypt";
import jwt from "jsonwebtoken";
import { private_key } from "../auth/private_key.js";
import User from "../models/user-model.js";

export const userLogin = async (req, res) => {
    try {
        const user = await User.findOne({ username: req.body.username });

        if (!user) {
            return res
                .status(404)
                .json({ message: `L'utilisateur n'existe pas.` });
        }

        const isPasswordValid = await bcrypt.compare(
            req.body.password,
            user.password,
        );

        if (!isPasswordValid) {
            return res
                .status(401)
                .json({ message: `Le mot de passe est incorrect.` });
        }

        const token = jwt.sign(
            { userId: user._id, uName: user.username },
            private_key,
            { expiresIn: "2h" },
        );

        return res.json({
            msg: "L'utilisateur a été trouvé et connecté avec succès",
            data: user.username,
            token,
        });
    } catch (error) {
        return res.status(500).json({ message: "Erreur serveur", data: error });
    }
};
