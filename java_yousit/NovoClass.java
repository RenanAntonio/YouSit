/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package com.br;

import java.sql.SQLException;
import java.util.List;

/**
 *
 * @author Administrador
 */
public class NovoClass {
    public static void main (String[] args0) throws SQLException{
        List<Reserva> l = new ReservaDAO().listaReservas();
        
        for (int i =0;i<l.size();i++){
            System.out.println(l.get(i));
        }
    }
}
